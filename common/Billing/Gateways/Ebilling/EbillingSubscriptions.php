<?php

namespace Common\Billing\Gateways\Ebilling;

use Common\Billing\GatewayException;
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
        // For Ebilling, changing plan means canceling current subscription
        // and creating a new one with new price
        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        // The user will need to create a new subscription manually
        return true;
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
        // For Ebilling, resuming means creating a new e-bill
        // This should redirect user to create a new payment
        throw new GatewayException(
            'Cannot resume Ebilling subscription. Please create a new subscription.',
        );
    }

    public function find(Subscription $subscription)
    {
        // For Ebilling, check if we have a gateway_id (bill_id) and verify its status
        if ($subscription->gateway_id) {
            try {
                $settings = app(\Common\Settings\Settings::class);
                $baseUrl = $settings->get('billing.ebilling_test_mode')
                    ? "https://lab.billing-easy.net/api/v1/merchant/e_bills/{$subscription->gateway_id}"
                    : "https://stg.billing-easy.com/api/v1/merchant/e_bills/{$subscription->gateway_id}";

                $username = $settings->get('billing.ebilling_username') ?? config('services.ebilling.username');
                $sharedkey = $settings->get('billing.ebilling_shared_key') ?? config('services.ebilling.sharedkey');

                $response = \Illuminate\Support\Facades\Http::withBasicAuth($username, $sharedkey)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ])
                    ->get($baseUrl);

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

        // Default response
        return [
            'renews_at' => $subscription->ends_at ?? now()->addMonth(),
            'status' => $subscription->status
        ];
    }
}
