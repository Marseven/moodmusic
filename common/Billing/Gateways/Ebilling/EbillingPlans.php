<?php

namespace Common\Billing\Gateways\Ebilling;

use Common\Billing\Models\Price;
use Common\Billing\Models\Product;

class EbillingPlans
{
    use InteractsWithEbillingRestApi;

    public function sync(Product $product): bool
    {
        // For Ebilling, we don't need to sync plans since each payment is a one-time e-bill
        // The plans are managed locally in the database
        // Just mark prices as available for Ebilling
        $product->load('prices');
        
        $product->prices->each(function (Price $price) {
            if (!$price->ebilling_id) {
                // Use the local price ID as the ebilling_id for reference
                $price->fill(['ebilling_id' => 'local_' . $price->id])->save();
            }
        });

        return true;
    }

    protected function create(Product $product, Price $price): bool
    {
        // For Ebilling, no need to create plans remotely
        // Plans are local and e-bills are created per transaction
        $price->fill(['ebilling_id' => 'local_' . $price->id])->save();
        return true;
    }

    public function delete(Product $product): bool
    {
        // For Ebilling, just remove the ebilling_id reference
        $product->prices->each(function (Price $price) {
            $price->fill(['ebilling_id' => null])->save();
        });

        return true;
    }
}
