<?php

namespace App\Services\Purchase;

use App\Purchase;
use Common\Settings\Settings;
use Illuminate\Support\Str;

class CreatePurchase
{
    public function execute(array $data): Purchase
    {
        $settings = app(Settings::class);
        $commissionRate = (float) $settings->get('sales.commission_rate', 30);
        $amount = (float) $data['amount'];

        $commissionAmount = round($amount * $commissionRate / 100, 2);
        $artistAmount = round($amount - $commissionAmount, 2);

        return Purchase::create([
            'user_id' => $data['user_id'],
            'purchasable_type' => $data['purchasable_type'],
            'purchasable_id' => $data['purchasable_id'],
            'gateway_name' => $data['gateway_name'],
            'amount' => $amount,
            'currency' => $data['currency'] ?? 'XAF',
            'commission_rate' => $commissionRate,
            'commission_amount' => $commissionAmount,
            'artist_amount' => $artistAmount,
            'status' => 'pending',
            'reference' => 'pur_' . Str::random(24),
        ]);
    }
}
