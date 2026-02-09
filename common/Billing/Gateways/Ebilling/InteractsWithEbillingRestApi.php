<?php

namespace Common\Billing\Gateways\Ebilling;

use Common\Settings\Settings;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

trait InteractsWithEbillingRestApi
{
    public function getApiBaseUrl(): string
    {
        $testMode = app(Settings::class)->get('billing.ebilling_test_mode', env('EBILLING_TEST_MODE', false));
        return $testMode
            ? config('services.ebilling.api_url_test', 'https://lab.billing-easy.net')
            : config('services.ebilling.api_url', 'https://stg.billing-easy.com');
    }

    public function getPortalBaseUrl(): string
    {
        $testMode = app(Settings::class)->get('billing.ebilling_test_mode', env('EBILLING_TEST_MODE', false));
        return $testMode
            ? config('services.ebilling.portal_url_test', 'https://test.billing-easy.net')
            : config('services.ebilling.portal_url', 'https://staging.billing-easy.net');
    }

    public function getEbillingCredentials(): array
    {
        $settings = app(Settings::class);
        return [
            'username' => $settings->get('billing.ebilling_username') ?? config('services.ebilling.username') ?? env('EBILLING_USERNAME'),
            'sharedkey' => $settings->get('billing.ebilling_shared_key') ?? config('services.ebilling.sharedkey') ?? env('EBILLING_SHAREDKEY'),
        ];
    }

    public function ebilling(): PendingRequest
    {
        $credentials = $this->getEbillingCredentials();

        return Http::baseUrl($this->getApiBaseUrl())
            ->withBasicAuth($credentials['username'], $credentials['sharedkey'])
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]);
    }
}
