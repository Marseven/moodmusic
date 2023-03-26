<?php

namespace App\Modules\AIM;

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

        echo "<!DOCTYPE html>
    <html>
	<body>
    <script src=\"https://aimtoget.com/assets/webpay/inline.js\"></script>
    <script>
    function callAtgPay(e) {
        AtgPayment.pay({
            email: '" . auth()->user()->email . "',
            phone: '+2349061668519',
            description: '" . $service->title . "',
            amount: " . round($service->price) . ",
            reference: '" . $service->title . "',
            key:'" . config('payment.gateway.aim.public_key') . "',
            logo_url: '" . asset('skins/default/images/small-logo.png') . "',
            onclose: function () {
            },
            onerror: function (data) {
                let reference = data.reference
                alert('There was an error, please close this window and try again');
            },
            onsuccess: function (data) {
                let reference = data.reference
                window.location = '" . route('frontend.aim.subscription.callback', ['id' => $this->request->route('id')]) . "';
            }
        })
    }
    window.onload=function(){
        callAtgPay()
    };
    </script>
  </body>
</html>";

        exit;
    }

    public function subscriptionCallback()
    {
        if(auth()->user()->subscription) {
            abort(403, 'You are already have a subscription.');
        }

        $service = Service::findOrFail($this->request->route('id'));

        $subscription = new Subscription();
        $subscription->gate = 'flutterwave';
        $subscription->user_id = auth()->user()->id;
        $subscription->service_id = $service->id;
        $subscription->payment_status = 1;
        $subscription->transaction_id = $this->request->input('transaction_id');
        $subscription->token = $this->request->input('transaction_id');
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

        echo "<!DOCTYPE html>
    <html>
	<body>
    <script src=\"https://aimtoget.com/assets/webpay/inline.js\"></script>
    <script>
    function callAtgPay(e) {
        AtgPayment.pay({
            email: '" . auth()->user()->email . "',
            phone: '+2349061668519',
            description: 'Pay for Subscription',
            amount: " . round(Cart::getTotal()) . ",
            reference: '" . implode('|', $items) . "',
            key:'" . config('payment.gateway.aim.public_key') . "',
            logo_url: '" . asset('skins/default/images/small-logo.png') . "',
            onclose: function () {
                
            },
            onerror: function (data) {
                let reference = data;
                console.log(reference);
                alert('There was an error, please close this window and try again');
            },
            onsuccess: function (data) {
            console.log(data);
                //let reference = data.reference;
                //window.location = '" . route('frontend.aim.purchase.callback') . "?ref=' + reference;
            }
        })
    }
    window.onload=function(){
        callAtgPay()
    };
    </script>
  </body>
</html>";
        exit;
    }

    public function purchaseCallback()
    {
        $this->request->validate([
            'ref' => 'required|string',
        ]);

        $url = 'https://aimtoget.com/api/v1/payment/verify/' . $this->request->input('ref');

        $request = $this->authenticate();

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$request->accessToken}",
        ])->get($url);

        Cart::session(auth()->user()->id);

        foreach (Cart::getContent() as $item) {
            $order = new Order();
            $order->user_id = auth()->user()->id;
            $order->orderable_id = $item->attributes->orderable_id;
            $order->orderable_type = $item->attributes->orderable_type;
            $order->payment = 'aim';
            $order->amount = $item->price;
            $order->currency = config('settings.currency', 'USD');
            $order->payment_status = 1;
            $order->transaction_id = $this->request->input('transaction_id');
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
    }
}
