<?php

namespace Common\Settings\Validators;

use Common\Settings\Settings;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class EbillingCredentialsValidator implements SettingsValidator
{
    const KEYS = [
        'ebilling_username',
        'ebilling_shared_key',
        'billing.ebilling_test_mode',
    ];

    /**
     * @var Settings
     */
    private $settings;

    /**
     * @param Settings $settings
     */
    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    public function fails($settings)
    {
        $this->setConfigDynamically($settings);

        // Test authentication with a simple API call
        try {
            $testMode = $settings['billing.ebilling_test_mode'] ?? $this->settings->get('billing.ebilling_test_mode');
            $baseUrl = $testMode
                ? config('services.ebilling.api_url_test', 'https://lab.billing-easy.net') . '/api/v1/merchant'
                : config('services.ebilling.api_url', 'https://stg.billing-easy.com') . '/api/v1/merchant';

            $username = $settings['ebilling_username'] ?? config('services.ebilling.username');
            $sharedKey = $settings['ebilling_shared_key'] ?? config('services.ebilling.sharedkey');

            // Test with a simple auth check - attempt to get billing info
            $response = Http::withBasicAuth($username, $sharedKey)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->timeout(10)
                ->get($baseUrl . '/merchant');

            if (!$response->successful()) {
                return $this->getErrorMessage($response->json());
            }
        } catch (ClientException $e) {
            return $this->getDefaultError();
        } catch (\Exception $e) {
            return $this->getDefaultError();
        }
    }

    private function setConfigDynamically($settings)
    {
        foreach (self::KEYS as $key) {
            if (!Arr::has($settings, $key)) {
                continue;
            }

            if ($key === 'billing.ebilling_test_mode') {
                $this->settings->set(
                    'billing.ebilling_test_mode',
                    $settings[$key],
                );
            } else {
                // ebilling_username => username, ebilling_shared_key => sharedkey
                $configKey = str_replace(['ebilling_', '_'], ['', ''], $key);
                if ($key === 'ebilling_shared_key') {
                    $configKey = 'sharedkey';
                }

                Config::set("services.ebilling.$configKey", $settings[$key]);
            }
        }
    }

    /**
     * @param array $data
     * @return array
     */
    private function getErrorMessage($data)
    {
        $message = Arr::get($data, 'message');
        if (isset($data['name']) && $data['name'] === 'AUTHENTICATION_FAILURE') {
            return [
                'ebilling_group' =>
                'Ebilling Username or Shared Key is invalid.',
            ];
        } elseif ($message) {
            $infoLink = Arr::get($data, 'information_link');
            return ['ebilling_group' => "$message. $infoLink"];
        } else {
            return $this->getDefaultError();
        }
    }

    private function getDefaultError()
    {
        return ['ebilling_group' => 'These Ebilling credentials are not valid.'];
    }
}
