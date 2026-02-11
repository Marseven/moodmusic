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
        Log::channel('ebilling')->info('[webhook] Received', ['payload' => $payload]);

        // Extract reference and bill_id from webhook payload
        // eBilling notification params: billingid, transactionid, reference, payer_id, payer_code, amount
        $reference = $payload['external_reference'] ?? $payload['reference'] ?? null;
        $billId = $payload['bill_id'] ?? $payload['billingid'] ?? $payload['transaction_id'] ?? $payload['transactionid'] ?? null;

        if (!$reference && !$billId) {
            Log::channel('ebilling')->warning('[webhook] Missing reference and bill_id', ['payload' => $payload]);
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
            Log::channel('ebilling')->error('[webhook] Subscription not found', [
                'reference' => $reference,
                'bill_id' => $billId,
            ]);
            return response('Subscription not found', 404);
        }

        Log::channel('ebilling')->info('[webhook] Subscription found', [
            'subscription_id' => $subscription->id,
            'user_id' => $subscription->user_id,
            'reference' => $reference,
            'bill_id' => $billId,
        ]);

        // Verify the payment status directly with eBilling API
        // instead of trusting the webhook payload
        $verifiedStatus = $this->verifyPaymentWithEbilling($billId ?? $subscription->gateway_id);

        if ($verifiedStatus === null) {
            Log::channel('ebilling')->error('[webhook] API verification failed, rejecting', [
                'subscription_id' => $subscription->id,
                'bill_id' => $billId,
            ]);
            return response('Payment verification failed', 500);
        }

        Log::channel('ebilling')->info('[webhook] API verification result', [
            'subscription_id' => $subscription->id,
            'verified_status' => $verifiedStatus,
        ]);

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

            Log::channel('ebilling')->error('[webhook:verify] API request failed', [
                'bill_id' => $billId,
                'http_status' => $response->status(),
                'response_body' => $response->body(),
            ]);
        } catch (\Exception $e) {
            Log::channel('ebilling')->error('[webhook:verify] Exception', [
                'bill_id' => $billId,
                'error' => $e->getMessage(),
                'file' => $e->getFile() . ':' . $e->getLine(),
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

                Log::channel('ebilling')->info('[webhook] Payment confirmed (API-verified)', [
                    'subscription_id' => $subscription->id,
                    'user_id' => $subscription->user_id,
                    'bill_id' => $billId,
                    'renews_at' => $subscription->renews_at,
                ]);
                break;

            case 'failed':
            case 'cancelled':
                $subscription->update(['ends_at' => now()]);
                if ($subscription->user) {
                    $subscription->user->notify(new PaymentFailed($subscription));
                }
                Log::channel('ebilling')->warning('[webhook] Payment failed/cancelled (API-verified)', [
                    'subscription_id' => $subscription->id,
                    'user_id' => $subscription->user_id,
                    'bill_id' => $billId,
                    'status' => $normalizedStatus,
                ]);
                break;

            case 'pending':
            case 'ready':
                Log::channel('ebilling')->info('[webhook] Payment still pending (API-verified)', [
                    'subscription_id' => $subscription->id,
                    'bill_id' => $billId,
                    'status' => $normalizedStatus,
                ]);
                break;

            default:
                Log::channel('ebilling')->warning('[webhook] Unrecognized status from API', [
                    'status' => $status,
                    'subscription_id' => $subscription->id,
                    'bill_id' => $billId,
                ]);
        }
    }
}
