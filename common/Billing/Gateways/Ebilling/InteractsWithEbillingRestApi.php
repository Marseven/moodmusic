<?php

namespace Common\Billing\Gateways\Paypal;

use Carbon\Carbon;
use Common\Settings\Settings;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

trait InteractsWithEbillingRestApi
{
    protected string|null $accessToken = null;
    protected Carbon|null $tokenExpires = null;

    public function ebilling(): PendingRequest
    {
        $baseUrl = app(Settings::class)->get('billing.ebilling_test_mode')
            ? 'https://lab.billing-easy.net'
            : 'https://stg.billing-easy.com';

        if (
            !$this->accessToken ||
            $this->tokenExpires->lessThan(Carbon::now())
        ) {
            $clientId = config('services.ebilling.client_id');
            $secret = config('services.ebilling.secret');
            $response = Http::withBasicAuth($clientId, $secret)
                ->asForm()
                ->post("$baseUrl/oauth2/token", [
                    'grant_type' => 'client_credentials',
                ]);
            if (!$response->successful()) {
                $response->throw();
            }
            $this->accessToken = $response['access_token'];
            $this->tokenExpires = Carbon::now()->addSeconds(
                $response['expires_in'],
            );
        }

        return Http::withToken($this->accessToken)->baseUrl($baseUrl);
    }
}
