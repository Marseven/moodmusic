<?php

namespace Common\Billing\Gateways\Ebilling;

use App\User;
use Common\Billing\Gateways\Contracts\CommonSubscriptionGatewayActions;
use Common\Billing\Models\Price;
use Common\Billing\Models\Product;
use Common\Billing\Subscription;
use Common\Settings\Settings;

class Ebilling implements CommonSubscriptionGatewayActions
{
    use InteractsWithEbillingRestApi;

    public function __construct(
        protected Settings $settings,
        protected EbillingPlans $plans,
        protected EbillingSubscriptions $subscriptions,
    ) {}

    public function isEnabled(): bool
    {
        return (bool) app(Settings::class)->get('billing.ebilling.enable');
    }

    public function syncPlan(Product $product): bool
    {
        return $this->plans->sync($product);
    }

    public function deletePlan(Product $product): bool
    {
        return $this->plans->delete($product);
    }

    public function storeSubscriptionDetailsLocally(
        string $ebillingSubscriptionId,
        User $user,
    ): bool {
        // For Ebilling, this method should create a local subscription record
        // based on the successful payment information from the frontend
        // The ebillingSubscriptionId here is actually the subscription ID from our system
        
        $subscription = Subscription::find($ebillingSubscriptionId);
        
        if ($subscription && $subscription->user_id === $user->id) {
            $subscription->update([
                'status' => 'active',
                'gateway_id' => $ebillingSubscriptionId,
                'paid_at' => now(),
            ]);
            return true;
        }

        return false;
    }

    public function changePlan(
        Subscription $subscription,
        Product $newProduct,
        Price $newPrice,
    ): bool {
        return $this->subscriptions->changePlan(
            $subscription,
            $newProduct,
            $newPrice,
        );
    }

    public function cancelSubscription(
        Subscription $subscription,
        bool $atPeriodEnd = true,
    ): bool {
        return $this->subscriptions->cancel($subscription, $atPeriodEnd);
    }

    public function resumeSubscription(
        Subscription $subscription,
        array $gatewayParams = [],
    ): bool {
        return $this->subscriptions->resume($subscription, $gatewayParams);
    }

    public function findSubscription(Subscription $subscription): array
    {
        return $this->subscriptions->find($subscription);
    }
}
