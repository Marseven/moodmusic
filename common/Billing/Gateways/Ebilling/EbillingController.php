<?php

namespace Common\Billing\Gateways\Ebilling;

use App\User;
use Common\Billing\Models\Price;
use Common\Billing\Models\Product;
use Common\Billing\Subscription;
use Common\Core\BaseController;
use Common\Settings\Settings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EbillingController extends BaseController
{

    use InteractsWithEbillingRestApi;

    public function __construct(
        protected Request $request,
        protected Subscription $subscription,
        protected Ebilling $ebilling
    ) {
        $this->middleware('auth');
    }

    // common/Billing/Gateways/Ebilling/EbillingController.php
    public function createOrder(Request $request): JsonResponse
    {
        $settings = app(Settings::class);

        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'price_id' => 'required|integer|exists:prices,id',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $product = Product::findOrFail($data['product_id']);
        $price = Price::findOrFail($data['price_id']);
        $user = User::findOrFail($data['user_id']);

        $eb_amount = number_format($price->amount, 2, '.', '');
        $eb_reference = 'sub_' . $product->id . '_' . $price->id . '_' . uniqid();
        $eb_shortdescription = "Abonnement {$product->name}";
        $eb_name = $user->first_name . ' ' . $user->last_name;
        $eb_email = $user->email;
        $eb_msisdn = "074010203";
        $expiry_period = 60;

        // Préparer la facture
        $globalPayload = [
            'payer_email' => $eb_email,
            'payer_msisdn' => $eb_msisdn,
            'amount' => $eb_amount,
            'short_description' => $eb_shortdescription,
            'external_reference' => $eb_reference,
            'payer_name' => $eb_name,
            'expiry_period' => $expiry_period
        ];

        // Appel Ebilling
        try {
            // Create local subscription first
            $subscription = $user->subscribe('ebilling', null, $price);
            $subscription->update([
                'reference' => $eb_reference,
                'status' => 'pending',
            ]);

            $ebillingUrl = $settings->get('billing.ebilling_test_mode')
                ? 'https://lab.billing-easy.net/api/v1/merchant/e_bills'
                : 'https://stg.billing-easy.com/api/v1/merchant/e_bills';

            $username = config('services.ebilling.username');
            $sharedKey = config('services.ebilling.sharedkey');

            $content = json_encode($globalPayload);
            $curl = curl_init($ebillingUrl);
            curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $sharedKey);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
            $json_response = curl_exec($curl);
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($status < 200 || $status > 299) {
                // Delete the subscription if API call failed
                $subscription->delete();
                
                Log::error("Erreur Ebilling", [
                    'status' => $status,
                    'response' => $json_response,
                    'curl_error' => curl_error($curl),
                    'curl_errno' => curl_errno($curl),
                    'payload' => $globalPayload
                ]);
                return response()->json([
                    'message' => "Erreur $status : appel à Ebilling échoué."
                ], $status);
            }

            $response = json_decode($json_response, true);
            $bill_id = $response['e_bill']['bill_id'];
            
            // Update subscription with bill_id
            $subscription->update(['gateway_id' => $bill_id]);

            // ------------------------------
            // Construction de l’URL de redirection
            // ------------------------------

            // Générer l'URL de retour pour ce paiement spécifique
            $callback_url = url("/billing/checkout/{$product->id}/{$price->id}/ebilling/done") . '?invoice=' . $bill_id;

            $paymentPortalUrl = $settings->get('billing.ebilling_test_mode')
                ? 'https://test.billing-easy.net'
                : 'https://staging.billing-easy.net';

            return response()->json([
                'bill_id' => $bill_id,
                'checkout_url' => "{$paymentPortalUrl}?invoice={$bill_id}&redirect_url={$callback_url}",
            ]);
        } catch (\Throwable $e) {
            // Clean up subscription if it was created
            if (isset($subscription)) {
                $subscription->delete();
            }
            
            Log::error("Exception Ebilling: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => $data['user_id'],
                'product_id' => $data['product_id'],
                'price_id' => $data['price_id']
            ]);
            
            return response()->json([
                'message' => 'Erreur serveur. Veuillez réessayer.',
                'error_code' => 'EBILLING_EXCEPTION'
            ], 500);
        }
    }

    // common/Billing/Gateways/Ebilling/EbillingController.php

    public function handleReturn(Request $request)
    {
        $billId = $request->input('invoice');

        if (!$billId) {
            return redirect('/billing/failed?reason=missing_reference');
        }

        // Vérifier le statut réel auprès d'eBilling
        $settings = app(Settings::class);
        $baseUrl = $settings->get('billing.ebilling_test_mode')
            ? "https://lab.billing-easy.net/api/v1/merchant/e_bills/{$billId}"
            : "https://stg.billing-easy.com/api/v1/merchant/e_bills/{$billId}";

        // Configuration des identifiants
        $username = $settings->get('billing.ebilling_username') ?? config('services.ebilling.username');
        $sharedkey = $settings->get('billing.ebilling_shared_key') ?? config('services.ebilling.sharedkey');

        // Création de la requête HTTP de base avec authentification
        $response = Http::baseUrl($baseUrl)
            ->withBasicAuth($username, $sharedkey)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->get('');

        if (!$response->successful()) {
            return redirect('/billing/failed?reason=payment_verification_failed');
        }

        $paymentStatus = $response->json()['state'];
        $reference = $response->json()['external_reference'];

        // Trouver la subscription
        $subscription = Subscription::where('reference', $reference)->first();

        if (!$subscription) {
            return redirect('/billing/subscriptions?reason=subscription_not_found');
        }

        switch ($paymentStatus) {
            case 'paid':
                // Mettre à jour le statut localement
                $subscription->update([
                    'status' => 'active',
                    'paid_at' => now()
                ]);

                return redirect("/billing/subscriptions?subscription_id={$subscription->id}");

            case 'processed':
                // Mettre à jour le statut localement
                $subscription->update([
                    'status' => 'active',
                    'paid_at' => now()
                ]);

                return redirect("/billing/subscriptions?subscription_id={$subscription->id}");

            case 'ready':
                return redirect("/billing/subscriptions?subscription_id={$subscription->id}");

            default:
                return redirect('/billing/subscriptions?reason=payment_' . $paymentStatus);
        }
    }

    public function verifyPayment(string $billId): JsonResponse
    {
        try {
            $settings = app(Settings::class);
            $baseUrl = $settings->get('billing.ebilling_test_mode')
                ? "https://lab.billing-easy.net/api/v1/merchant/e_bills/{$billId}"
                : "https://stg.billing-easy.com/api/v1/merchant/e_bills/{$billId}";

            $username = $settings->get('billing.ebilling_username') ?? config('services.ebilling.username');
            $sharedkey = $settings->get('billing.ebilling_shared_key') ?? config('services.ebilling.sharedkey');

            $response = Http::withBasicAuth($username, $sharedkey)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->get($baseUrl);

            if (!$response->successful()) {
                Log::error("Ebilling verification failed", [
                    'billId' => $billId,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return response()->json(['error' => 'Payment verification failed'], 400);
            }

            $data = $response->json();
            $status = $data['state'] === 'paid' ? 'PAID' : 'PENDING';
            $reference = $data['external_reference'] ?? null;
            
            // Trouver la subscription par référence
            $subscription = null;
            if ($reference) {
                $subscription = Subscription::where('reference', $reference)->first();
                
                if ($subscription && $status === 'PAID') {
                    $subscription->update([
                        'status' => 'active',
                        'paid_at' => now()
                    ]);
                }
            }

            return response()->json([
                'status' => $status,
                'subscription' => $subscription,
                'bill_data' => $data
            ]);

        } catch (\Exception $e) {
            Log::error("Ebilling verification error: " . $e->getMessage(), [
                'billId' => $billId,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Verification failed'], 500);
        }
    }

    public function storeSubscriptionDetailsLocally(): Response|JsonResponse
    {
        $data = $this->validate($this->request, [
            'ebilling_subscription_id' => 'required|string',
        ]);

        $this->ebilling->storeSubscriptionDetailsLocally(
            $data['ebilling_subscription_id'],
            Auth::user(),
        );

        return $this->success();
    }
}
