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

        Log::info('Ebilling changePlan: old subscription cancelled with grace period', [
            'subscription_id' => $subscription->id,
            'ends_at' => $subscription->ends_at,
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

        Log::info('Ebilling subscription cancelled locally', [
            'subscription_id' => $subscription->id,
            'at_period_end' => $atPeriodEnd
        ]);

        return true;
    }

    public function resume(Subscription $subscription, array $params): bool
    {
        // eBilling payments are one-time bills, not recurring subscriptions.
        // During grace period the user has already paid, so resuming is purely local:
        // restore renews_at from ends_at and clear ends_at.
        if (!$subscription->ends_at) {
            return false;
        }

        Log::info('Ebilling subscription resumed locally', [
            'subscription_id' => $subscription->id,
            'restored_renews_at' => $subscription->ends_at,
        ]);

        return true;
    }

    public function find(Subscription $subscription)
    {
        if ($subscription->gateway_id) {
            try {
                $response = $this->ebilling()->get("/api/v1/merchant/e_bills/{$subscription->gateway_id}");

                if ($response->successful()) {
                    $data = $response->json();
                    $nextBilling = $subscription->ends_at ?? now()->addMonth();

                    return [
                        'renews_at' => $nextBilling,
                        'status' => $data['state'] ?? 'unknown'
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Error finding Ebilling subscription', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return [
            'renews_at' => $subscription->ends_at ?? now()->addMonth(),
            'status' => $subscription->status
        ];
    }
}
