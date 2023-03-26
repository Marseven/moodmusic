<?php

namespace App\Modules\FlutterWave;

use App\Models\Order;
use App\Models\Service;
use Carbon\Carbon;
use Cart;
use Illuminate\Http\Request;
use App\Models\Email;
use App\Models\Subscription;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Controller
{
    protected $publicKey;
    protected $env = 'staging';
    protected $baseUrl;
    protected $request;
    protected $body;
    protected $amount;
    protected $description;
    protected $country;
    protected $currency;
    protected $email;
    protected $firstName;
    protected $lastName;
    protected $phoneNumber;
    protected $handler;
    protected $meta;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function subscriptionAuthorization()
    {
        if(auth()->user()->subscription) {
            abort(403, 'You are already have a subscription.');
        }

        $service = Service::findOrFail($this->request->route('id'));

        $checkoutData = array(
            'public_key' =>  config('payment.gateway.flutterwave.public_key'),
            'tx_ref' => rand(),
            'amount' => round($service->price),
            'currency' => config('settings.currency', 'USD'),
            'country' => 'NG',
            'payment_options' => 'card, mobilemoneyghana, ussd',
            'redirect_url' => route('frontend.flutterwave.subscription.callback', ['id' => $this->request->route('id')]),
            'meta' => [
                'consumer_id' => auth()->user()->id,
                'consumer_mac' => rand()
            ],
            'customer' => [
                'email' => auth()->user()->email,
                'phone_number' => '',
                'name' => auth()->user()->name,
            ],
            'customizations' => [
                'title' => env('APP_NAME'),
                'description' => $service->title,
                'logo' => asset('skins/default/images/small-logo.png')
            ]
        );

        echo '<html>';
        echo '<body>';
        echo '<center>Processing...<br /><img style="height: 50px;" src="https://media.giphy.com/media/swhRkVYLJDrCE/giphy.gif" /></center>';
        echo '<script type="text/javascript" src="https://checkout.flutterwave.com/v3.js"></script>';
        echo '<script>';
        echo 'document.addEventListener("DOMContentLoaded", function(event) {';
        echo 'FlutterwaveCheckout(' . \GuzzleHttp\json_encode($checkoutData) . ');';
        echo '});';
        echo '</script>';
        echo '</body>';
        echo '</html>';

        exit;
    }

    public function subscriptionCallback()
    {
        if(auth()->user()->subscription) {
            abort(403, 'You are already have a subscription.');
        }

        $response = Http::post(config('payment.gateway.flutterwave.environment') == 'live' ? 'https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify' :  'https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/v2/verify', [
            "SECKEY" => config('payment.gateway.flutterwave.secret_key'),
            "txref" => $this->request->input('tx_ref')
        ]);

        if ($response->successful() && $response->object()->data->txid == $this->request->input('transaction_id')) {
            $service = Service::findOrFail($this->request->route('id'));

            $subscription = new Subscription();
            $subscription->gate = 'flutterwave';
            $subscription->user_id = auth()->user()->id;
            $subscription->service_id = $service->id;
            $subscription->payment_status = 1;
            $subscription->transaction_id = $this->request->input('transaction_id');
            $subscription->token = $this->request->input('tx_ref');
            $subscription->trial_end = null;

            switch ($service->plan_period_format) {
                case 'D':
                    $next_billing_date = Carbon::now()->addDays($service->plan_period)->format('Y-m-d\TH:i:s\Z');
                    break;
                case 'W':
                    $next_billing_date = Carbon::now()->addWeeks($service->plan_period)->format('Y-m-d\TH:i:s\Z');
                    break;
                case 'M':
                    $next_billing_date = Carbon::now()->addMonths($service->plan_period)->format('Y-m-d\TH:i:s\Z');
                    break;
                case 'Y':
                    $next_billing_date = Carbon::now()->addYears($service->plan_period)->format('Y-m-d\TH:i:s\Z');
                    break;
                default:
                    $next_billing_date = date("Y-m-d\TH:i:s\Z", strtotime('+2 minute'));
            }

            $subscription->next_billing_date = $next_billing_date;
            $subscription->cycles = 1;
            $subscription->amount = $service->price;
            $subscription->currency = config('settings.currency', 'USD');

            if(! $service->trial) {
                $subscription->last_payment_date = Carbon::now();
            }

            $subscription->save();

            (new Email)->subscriptionReceipt(auth()->user(), $subscription);

            echo '<script type="text/javascript">
            var opener = window.opener;
            if(opener) {
                opener.Payment.subscriptionSuccess();
                window.close();
            }
            </script>';

            exit;
        }  else {
            abort(403, 'Unable to authenticate FlutterWave');
        }
    }

    public function purchaseAuthorization()
    {
        Cart::session(auth()->user()->id);

        if(Cart::isEmpty()) {
            abort(500,'Cart is empty');
        }

        $items = array();
        foreach (Cart::getContent() as $product) {
            $item = $product->associatedModel->title;
            $items[] = $item;
        }

        $checkoutData = array(
            'public_key' =>  config('payment.gateway.flutterwave.public_key'),
            'tx_ref' => rand(),
            'amount' => round(Cart::getTotal()),
            'currency' => config('settings.currency', 'USD'),
            'country' => 'NG',
            'payment_options' => 'card, mobilemoneyghana, ussd',
            'redirect_url' => route('frontend.flutterwave.purchase.callback'),
            'meta' => [
                'consumer_id' => auth()->user()->id,
                'consumer_mac' => rand()
            ],
            'customer' => [
                'email' => auth()->user()->email,
                'phone_number' => '',
                'name' => auth()->user()->name,
            ],
            'customizations' => [
                'title' => env('APP_NAME'),
                'description' => implode('|', $items),
                'logo' => asset('skins/default/images/small-logo.png')
            ]
        );

        echo '<html>';
        echo '<body>';
        echo '<center>Processing...<br /><img style="height: 50px;" src="https://media.giphy.com/media/swhRkVYLJDrCE/giphy.gif" /></center>';
        echo '<script type="text/javascript" src="https://checkout.flutterwave.com/v3.js"></script>';
        echo '<script>';
        echo 'document.addEventListener("DOMContentLoaded", function(event) {';
        echo 'FlutterwaveCheckout(' . \GuzzleHttp\json_encode($checkoutData) . ');';
        echo '});';
        echo '</script>';
        echo '</body>';
        echo '</html>';

        exit;
    }

    public function purchaseCallback()
    {
        $response = Http::post(config('payment.gateway.flutterwave.environment') == 'live' ? 'https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify' :  'https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/v2/verify', [
            "SECKEY" => config('payment.gateway.flutterwave.secret_key'),
            "txref" => $this->request->input('tx_ref')
        ]);

        if ($response->successful() && $response->object()->data->txid == $this->request->input('transaction_id')) {
            Cart::session(auth()->user()->id);

            foreach (Cart::getContent() as $item) {
                $order = new Order();
                $order->user_id = auth()->user()->id;
                $order->orderable_id = $item->attributes->orderable_id;
                $order->orderable_type = $item->attributes->orderable_type;
                $order->payment = 'flutterwave';
                $order->amount = $item->price;
                $order->currency = config('settings.currency', 'USD');
                $order->payment_status = 1;
                $order->transaction_id = $this->request->input('tx_ref');
                $order->save();
            }

            Cart::clear();

            echo '<script type="text/javascript">
                var opener = window.opener;
                if(opener) {
                    opener.Payment.purchaseSuccess();
                    window.close();
                }
                
             </script>';
            exit();

        } else {
            abort(403, 'Unable to authenticate FlutterWave');
        }
    }
}
