<?php

namespace Common\Billing\Gateways\Ebilling;

use Carbon\Carbon;
use Common\Settings\Settings;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

trait InteractsWithEbillingRestApi
{

    public function ebilling(): PendingRequest
    {
        $settings = app(Settings::class);
        $baseUrl = $settings->get('billing.ebilling_test_mode')
            ? 'https://lab.billing-easy.net'
            : 'https://stg.billing-easy.com';

        // Configuration des identifiants
        $username = $settings->get('billing.ebilling_username') ?? config('services.ebilling.username');
        $sharedkey = $settings->get('billing.ebilling_shared_key') ?? config('services.ebilling.sharedkey');

        // Création de la requête HTTP de base avec authentification
        return Http::baseUrl($baseUrl)
            ->withBasicAuth($username, $sharedkey)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]);
    }
}
