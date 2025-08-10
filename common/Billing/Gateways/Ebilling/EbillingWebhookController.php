<?php

namespace Common\Billing\Gateways\Ebilling;

use App\User;
use Common\Billing\GatewayException;
use Common\Billing\Gateways\Ebilling\Ebilling;
use Common\Billing\Invoices\CreateInvoice;
use Common\Billing\Notifications\PaymentFailed;
use Common\Billing\Subscription;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EbillingWebhookController extends Controller
{
    use InteractsWithEbillingRestApi;

    public function __construct(
        protected Subscription $subscription,
        protected Ebilling $ebilling,
    ) {}

    // common/Billing/Gateways/Ebilling/EbillingWebhookController.php

    public function handleWebhook(Request $request): Response
    {
        $payload = $request->all();
        Log::info('Webhook eBilling reçu', $payload);

        // Validation de la signature (optionnel mais recommandé)
        if (!$this->verifyWebhookSignature($request)) {
            Log::error('Signature webhook invalide', $payload);
            return response('Invalid signature', 403);
        }

        // Traitement selon le statut
        $reference = $payload['external_reference'] ?? null;
        $status = $payload['status'] ?? null;
        $transactionId = $payload['transaction_id'] ?? null;

        if (!$reference) {
            return response('Missing reference', 400);
        }

        // Extraire l'ID de subscription du référence (format: sub_{product_id}_{price_id}_{uniqid})
        $subscription = Subscription::where('reference', $reference)->first();

        if (!$subscription) {
            Log::error('Subscription not found', ['reference' => $reference]);
            return response('Subscription not found', 404);
        }

        switch ($status) {
            case 'PAID':
                $subscription->update([
                    'status' => 'active',
                    'gateway_id' => $transactionId,
                    'paid_at' => now(),
                    'ends_at' => now()->addMonths($subscription->plan->interval_count)
                ]);

            case 'FAILED':
                $subscription->update(['status' => 'failed']);
                $subscription->user->notify(new PaymentFailed($subscription));
                break;

            case 'PENDING':
                // Pas d'action nécessaire
                break;

            default:
                Log::warning('Statut eBilling non reconnu', ['status' => $status]);
                return response('Unhandled status', 200);
        }

        return response('Webhook processed', 200);
    }

    protected function verifyWebhookSignature(Request $request): bool
    {
        $signature = $request->header('X-Ebilling-Signature');
        $sharedSecret = config('services.ebilling.webhook_secret');

        $expectedSignature = hash_hmac('sha256', $request->getContent(), $sharedSecret);

        return hash_equals($expectedSignature, $signature);
    }

    protected function handleInvoicePaymentFailed(array $payload): Response
    {
        $ebillingSubscriptionId = Arr::get(
            $payload,
            'resource.billing_agreement_id',
        );

        $subscription = $this->subscription
            ->where('gateway_id', $ebillingSubscriptionId)
            ->first();
        $subscription?->user->notify(new PaymentFailed($subscription));

        return response('Webhook handled', 200);
    }

    protected function handleSubscriptionCancelledOrExpired(
        array $payload,
    ): Response {
        $ebillingSubscriptionId = $payload['resource']['id'];

        $subscription = $this->subscription
            ->where('gateway_id', $ebillingSubscriptionId)
            ->first();

        if ($subscription && !$subscription->cancelled()) {
            $subscription->markAsCancelled();
        }

        return response('Webhook Handled', 200);
    }

    protected function handleSaleCompleted(array $payload): Response
    {
        return response('Webhook Handled', 200);
    }

    protected function handleSubscriptionCreated(array $payload): Response
    {
        $ebillingSubscriptionId = Arr::get($payload, 'resource.id');
        $ebillingUserId = Arr::get($payload, 'resource.subscriber.payer_id');

        $user = User::where('ebilling_id', $ebillingUserId)->first();
        if ($user) {
            $this->ebilling->storeSubscriptionDetailsLocally(
                $ebillingSubscriptionId,
                $user,
            );
        }

        return response('Webhook Handled', 200);
    }
}
