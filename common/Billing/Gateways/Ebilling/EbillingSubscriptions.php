<?php

namespace Common\Billing\Gateways\Ebilling;

use Common\Billing\Models\Price;
use Common\Billing\Models\Product;
use Common\Billing\Subscription;
use Illuminate\Support\Facades\Log;

class EbillingSubscriptions
{
    use InteractsWithEbillingRestApi;

    public function changePlan(
        Subscription $subscription,
        Product $newProduct,
        Price $newPrice,
    ): bool {
        // eBilling has no remote subscription update API.
        // The old subscription is cancelled with a grace period (until renews_at).
        // A new e-bill must be created via EbillingController::changePlan().
        // Return false so Subscription::changePlan() does NOT update product/price
        // on the old subscription (which would be inconsistent without payment).
        $subscription->fill([
            'ends_at' => $subscription->renews_at ?? now()->addDays(30),
            'renews_at' => null,
        ])->save();

        Log::channel('ebilling')->info('[subscriptions:changePlan] Old subscription cancelled with grace period', [
            'subscription_id' => $subscription->id,
            'user_id' => $subscription->user_id,
            'ends_at' => $subscription->ends_at,
            'new_product' => $newProduct->name,
            'new_price' => $newPrice->amount,
        ]);

        return false;
    }

    public function cancel(
        Subscription $subscription,
        $atPeriodEnd = true,
    ): bool {
        // For Ebilling, we can't cancel remotely since there's no ongoing subscription
        // Just update locally
        if ($atPeriodEnd) {
            $subscription->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'ends_at' => $subscription->ends_at ?? now()->addDays(30), // grace period
            ]);
        } else {
            $subscription->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'ends_at' => now(),
            ]);
        }

        Log::channel('ebilling')->info('[subscriptions:cancel] Subscription cancelled', [
            'subscription_id' => $subscription->id,
            'user_id' => $subscription->user_id,
            'at_period_end' => $atPeriodEnd,
            'ends_at' => $subscription->ends_at,
        ]);

        return true;
    }

    public function resume(Subscription $subscription, array $params): bool
    {
        // eBilling payments are one-time bills, not recurring subscriptions.
        // During grace period the user has already paid, so resuming is purely local:
        // restore renews_at from ends_at and clear ends_at.
        if (!$subscription->ends_at) {
            Log::channel('ebilling')->warning('[subscriptions:resume] Cannot resume - no ends_at set', [
                'subscription_id' => $subscription->id,
                'user_id' => $subscription->user_id,
            ]);
            return false;
        }

        Log::channel('ebilling')->info('[subscriptions:resume] Subscription resumed', [
            'subscription_id' => $subscription->id,
            'user_id' => $subscription->user_id,
            'restored_renews_at' => $subscription->ends_at,
        ]);

        return true;
    }

    public function find(Subscription $subscription)
    {
        if ($subscription->gateway_id) {
            try {
                Log::channel('ebilling')->info('[subscriptions:find] Querying eBilling API', [
                    'subscription_id' => $subscription->id,
                    'gateway_id' => $subscription->gateway_id,
                ]);

                $response = $this->ebilling()->get("/api/v1/merchant/e_bills/{$subscription->gateway_id}");

                if ($response->successful()) {
                    $data = $response->json();
                    $nextBilling = $subscription->ends_at ?? now()->addMonth();

                    Log::channel('ebilling')->info('[subscriptions:find] API response received', [
                        'subscription_id' => $subscription->id,
                        'ebilling_state' => $data['state'] ?? 'unknown',
                        'next_billing' => $nextBilling,
                    ]);

                    return [
                        'renews_at' => $nextBilling,
                        'status' => $data['state'] ?? 'unknown'
                    ];
                }

                Log::channel('ebilling')->error('[subscriptions:find] API request failed', [
                    'subscription_id' => $subscription->id,
                    'http_status' => $response->status(),
                    'response_body' => $response->body(),
                ]);
            } catch (\Exception $e) {
                Log::channel('ebilling')->error('[subscriptions:find] Exception', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                    'file' => $e->getFile() . ':' . $e->getLine(),
                ]);
            }
        }

        return [
            'renews_at' => $subscription->ends_at ?? now()->addMonth(),
            'status' => $subscription->status
        ];
    }
}
