<?php

namespace Common\Billing\Gateways\Ebilling;

use Common\Billing\Invoices\CreateInvoice;
use Common\Billing\Notifications\PaymentFailed;
use Common\Billing\Subscription;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EbillingWebhookController extends Controller
{
    use InteractsWithEbillingRestApi;

    public function handleWebhook(Request $request): Response
    {
        $payload = $request->all();
        Log::info('Webhook eBilling reçu', ['payload' => $payload]);

        // Extract reference and bill_id from webhook payload
        // eBilling notification params: billingid, transactionid, reference, payer_id, payer_code, amount
        $reference = $payload['external_reference'] ?? $payload['reference'] ?? null;
        $billId = $payload['bill_id'] ?? $payload['billingid'] ?? $payload['transaction_id'] ?? $payload['transactionid'] ?? null;

        if (!$reference && !$billId) {
            Log::warning('Webhook eBilling: missing reference and bill_id', ['payload' => $payload]);
            return response('Missing reference or bill_id', 400);
        }

        // Find subscription by reference or gateway_id (bill_id)
        $subscription = null;
        if ($reference) {
            $subscription = Subscription::where('reference', $reference)->first();
        }
        if (!$subscription && $billId) {
            $subscription = Subscription::where('gateway_id', $billId)->first();
        }

        if (!$subscription) {
            Log::error('Webhook eBilling: subscription not found', [
                'reference' => $reference,
                'bill_id' => $billId,
            ]);
            return response('Subscription not found', 404);
        }

        // Verify the payment status directly with eBilling API
        // instead of trusting the webhook payload
        $verifiedStatus = $this->verifyPaymentWithEbilling($billId ?? $subscription->gateway_id);

        if ($verifiedStatus === null) {
            Log::error('Webhook eBilling: API verification failed, rejecting webhook', [
                'subscription_id' => $subscription->id,
                'bill_id' => $billId,
            ]);
            return response('Payment verification failed', 500);
        }

        $this->processVerifiedStatus($verifiedStatus, $subscription, $billId);

        return response('Webhook processed', 200);
    }

    protected function verifyPaymentWithEbilling(?string $billId): ?string
    {
        if (!$billId) {
            return null;
        }

        try {
            $response = $this->ebilling()->get("/api/v1/merchant/e_bills/{$billId}");

            if ($response->successful()) {
                return $response->json('state');
            }

            Log::error('Webhook eBilling: API verification request failed', [
                'bill_id' => $billId,
                'status' => $response->status(),
            ]);
        } catch (\Exception $e) {
            Log::error('Webhook eBilling: API verification exception', [
                'bill_id' => $billId,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    protected function processVerifiedStatus(string $status, Subscription $subscription, ?string $billId): void
    {
        $normalizedStatus = strtolower($status);

        switch ($normalizedStatus) {
            case 'paid':
            case 'processed':
                $updateData = [
                    'paid_at' => now(),
                    'ends_at' => null,
                    'renews_at' => now()->addMonths($subscription->price?->interval_count ?? 1),
                ];
                if ($billId) {
                    $updateData['gateway_id'] = $billId;
                }
                $subscription->update($updateData);

                app(CreateInvoice::class)->execute([
                    'subscription_id' => $subscription->id,
                    'paid' => true,
                ]);

                Log::info('Webhook eBilling: payment confirmed (API-verified)', [
                    'subscription_id' => $subscription->id,
                ]);
                break;

            case 'failed':
            case 'cancelled':
                $subscription->update(['ends_at' => now()]);
                if ($subscription->user) {
                    $subscription->user->notify(new PaymentFailed($subscription));
                }
                Log::info('Webhook eBilling: payment failed/cancelled (API-verified)', [
                    'subscription_id' => $subscription->id,
                ]);
                break;

            case 'pending':
            case 'ready':
                Log::info('Webhook eBilling: payment pending (API-verified)', [
                    'subscription_id' => $subscription->id,
                ]);
                break;

            default:
                Log::warning('Webhook eBilling: unrecognized status from API', [
                    'status' => $status,
                    'subscription_id' => $subscription->id,
                ]);
        }
    }
}
