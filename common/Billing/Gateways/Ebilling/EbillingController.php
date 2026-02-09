<?php

namespace Common\Billing\Gateways\Ebilling;

use App\User;
use Common\Billing\Models\Price;
use Common\Billing\Models\Product;
use Common\Billing\Subscription;
use Common\Core\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function createOrder(Request $request): JsonResponse
    {
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
        $eb_msisdn = $user->phone ?? '074000000';
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
            $credentials = $this->getEbillingCredentials();
            if (!$credentials['username'] || !$credentials['sharedkey']) {
                Log::error('eBilling credentials missing');
                return response()->json([
                    'message' => 'Configuration eBilling incomplète.',
                    'error_code' => 'EBILLING_CONFIG_MISSING'
                ], 500);
            }

            // Create local subscription in "pending" state:
            // ends_at is set to expiry_period so it stays in grace period until payment confirmed.
            // The webhook will set ends_at=null when payment is confirmed.
            $tempGatewayId = 'temp_' . uniqid();
            $subscription = $user->subscribe('ebilling', $tempGatewayId, $price);
            $subscription->update([
                'reference' => $eb_reference,
                'ends_at' => now()->addMinutes($expiry_period),
                'renews_at' => null,
            ]);

            $response = $this->ebilling()->post('/api/v1/merchant/e_bills', $globalPayload);

            if (!$response->successful()) {
                $subscription->delete();
                Log::error("Erreur Ebilling", [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'payload' => $globalPayload
                ]);
                return response()->json([
                    'message' => "Erreur {$response->status()} : appel à Ebilling échoué."
                ], $response->status());
            }

            $bill_id = $response->json('e_bill.bill_id');
            $subscription->update(['gateway_id' => $bill_id]);

            $callback_url = url("/checkout/{$product->id}/{$price->id}/ebilling/done") . '?invoice=' . $bill_id;
            $portalUrl = $this->getPortalBaseUrl();

            return response()->json([
                'bill_id' => $bill_id,
                'checkout_url' => "{$portalUrl}?invoice={$bill_id}&redirect_url={$callback_url}",
            ]);
        } catch (\Throwable $e) {
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

    public function verifyPayment(string $billId): JsonResponse
    {
        try {
            $response = $this->ebilling()->get("/api/v1/merchant/e_bills/{$billId}");

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

            $subscription = null;
            if ($reference) {
                $subscription = Subscription::where('reference', $reference)->first();

                if ($subscription && $status === 'PAID') {
                    $subscription->update([
                        'paid_at' => now(),
                        'ends_at' => null,
                        'renews_at' => now()->addMonths($subscription->price?->interval_count ?? 1),
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

    public function changePlan(Request $request): JsonResponse
    {
        $data = $request->validate([
            'subscription_id' => 'required|integer|exists:subscriptions,id',
            'newProductId' => 'required|integer|exists:products,id',
            'newPriceId' => 'required|integer|exists:prices,id',
        ]);

        $oldSubscription = Subscription::findOrFail($data['subscription_id']);
        $newProduct = Product::findOrFail($data['newProductId']);
        $newPrice = Price::findOrFail($data['newPriceId']);
        $user = Auth::user();

        if ($oldSubscription->user_id !== $user->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        try {
            $credentials = $this->getEbillingCredentials();
            if (!$credentials['username'] || !$credentials['sharedkey']) {
                return response()->json([
                    'message' => 'Configuration eBilling incomplète.',
                    'error_code' => 'EBILLING_CONFIG_MISSING',
                ], 500);
            }

            // 1. Cancel old subscription with grace period
            $oldSubscription->fill([
                'ends_at' => $oldSubscription->renews_at ?? now()->addDays(30),
                'renews_at' => null,
            ])->save();

            // 2. Prepare new e-bill payload
            $eb_reference = 'sub_' . $newProduct->id . '_' . $newPrice->id . '_' . uniqid();
            $globalPayload = [
                'payer_email' => $user->email,
                'payer_msisdn' => $user->phone ?? '074000000',
                'amount' => number_format($newPrice->amount, 2, '.', ''),
                'short_description' => "Changement plan - {$newProduct->name}",
                'external_reference' => $eb_reference,
                'payer_name' => $user->first_name . ' ' . $user->last_name,
                'expiry_period' => 60,
            ];

            // 3. Create new local subscription in "pending" state
            $tempGatewayId = 'temp_' . uniqid();
            $newSubscription = $user->subscribe('ebilling', $tempGatewayId, $newPrice);
            $newSubscription->update([
                'reference' => $eb_reference,
                'ends_at' => now()->addMinutes(60),
                'renews_at' => null,
            ]);

            // 4. Call eBilling API
            $response = $this->ebilling()->post('/api/v1/merchant/e_bills', $globalPayload);

            if (!$response->successful()) {
                $newSubscription->delete();
                Log::error('Ebilling changePlan API error', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
                return response()->json([
                    'message' => "Erreur eBilling ({$response->status()}). Changement de plan annulé.",
                ], 500);
            }

            $bill_id = $response->json('e_bill.bill_id');

            // 5. Update new subscription with bill_id
            $newSubscription->update(['gateway_id' => $bill_id]);

            // 6. Build checkout URL
            $callback_url = url("/checkout/{$newProduct->id}/{$newPrice->id}/ebilling/done") . '?invoice=' . $bill_id;
            $portalUrl = $this->getPortalBaseUrl();

            Log::info('Ebilling changePlan: new e-bill created', [
                'old_subscription_id' => $oldSubscription->id,
                'new_subscription_id' => $newSubscription->id,
                'bill_id' => $bill_id,
            ]);

            return response()->json([
                'bill_id' => $bill_id,
                'checkout_url' => "{$portalUrl}?invoice={$bill_id}&redirect_url={$callback_url}",
                'old_subscription_id' => $oldSubscription->id,
                'new_subscription_id' => $newSubscription->id,
            ]);
        } catch (\Throwable $e) {
            Log::error('Ebilling changePlan exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => 'Erreur serveur. Veuillez réessayer.',
                'error_code' => 'EBILLING_CHANGE_PLAN_ERROR',
            ], 500);
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
