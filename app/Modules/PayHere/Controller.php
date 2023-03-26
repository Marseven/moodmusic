<?php

namespace App\Modules\PayHere;

use App\Models\Order;
use App\Models\Service;
use Carbon\Carbon;
use Cart;
use Illuminate\Http\Request;
use App\Models\Email;
use App\Models\Subscription;
use Illuminate\Support\Facades\Http;

class Controller
{
    const URI_GENERATE_TOKEN = 'merchant/v1/oauth/token';
    const URI_RETRIEVAL_ORDER_DETAIL = 'merchant/v1/payment/search';
    const URI_ALL_SUBSCRIPTIONS = 'merchant/v1/subscription';
    const URI_RETRY_SUBSCRIPTIONS = 'merchant/v1/subscription/retry';
    const URI_CANCEL_SUBSCRIPTIONS = 'merchant/v1/subscription/cancel';

    const CURRENCY_LKR = 'LKR';
    const CURRENCY_USD = 'USD';
    const CURRENCY_GBP = 'GBP';
    const CURRENCY_EUR = 'EUR';
    const CURRENCY_AUD = 'AUD';

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    private function authenticate()
    {
        $appId = config('payment.gateway.payhere.business_app_id');
        $appSecret = config('payment.gateway.payhere.business_app_secret_key');
        $token = base64_encode("{$appId}:{$appSecret}");

        $response = Http::withHeaders([
            'Authorization' => "Basic {$token}",
        ])->asForm()->post(config('payment.gateway.payhere.base_url') . self::URI_GENERATE_TOKEN, [
            'grant_type' => 'client_credentials',
        ]);

        if ($response->successful()) {
            $this->accessToken = $response->object()->access_token;
            return $this;
        }

        abort(500, 'Unable to authenticate PayHere');
    }

    public function subscriptionAuthorization()
    {
        if(auth()->user()->subscription) {
            abort(403, 'You are already have a subscription.');
        }

        $service = Service::findOrFail($this->request->route('id'));

        echo "<form style='visibility: hidden;' name=\"payment\" id=\"payment\"  method=\"post\" action=\"https://sandbox.payhere.lk/pay/checkout\" >  
            <input type=\"hidden\" name=\"merchant_id\" value=\"" . config('payment.gateway.payhere.merchant_id') . "\">
            <input type=\"hidden\" name=\"return_url\" value=\"" . route('frontend.payhere.subscription.callback', ['id' => $this->request->route('id')]) . "\">
            <input type=\"hidden\" name=\"cancel_url\" value=\"" . route('frontend.payhere.subscription.callback', ['id' => $this->request->route('id')]) . "\">
            <input type=\"hidden\" name=\"notify_url\" value=\"" . route('frontend.payhere.subscription.callback', ['id' => $this->request->route('id')]) . "\">

            <input hidden type=\"text\" name=\"order_id\" value=\"" . rand(1, 1000000) . "\">
            <input hidden type=\"text\" name=\"items\" value=\"" . $service->title . " Subscription\">
            <input hidden type=\"text\" name=\"currency\" value=\"" . config('settings.currency', 'USD') . "\">
            <input hidden type=\"text\" name=\"amount\" value=\"" . $service->price . "\"> 

            <br><br>Customer Details</br></br>
            <input type=\"text\" name=\"first_name\" value=\"" . auth()->user()->name . "\">
            <input type=\"text\" name=\"last_name\" value=\"" . auth()->user()->username . "\">
            <input type=\"text\" name=\"email\" value=\"" . auth()->user()->email . "\">
            <input type=\"text\" name=\"address\" value=\"" . env('APP_NAME') . "\">
            <input type=\"text\" name=\"city\" value=\"" . env('APP_NAME') . "\">
            <input type=\"hidden\" name=\"country\" value=\"" . env('APP_NAME') . "\">
            <input type=\"submit\" value=\"Submit\">
        </form>
        <script type=\"text/javascript\">
            window.onload = function(){
              document.forms['payment'].submit();
            }
        </script>";

        exit;

    }

    public function subscriptionCallback()
    {
        if(auth()->user()->subscription) {
            abort(403, 'You are already have a subscription.');
        }

        $this->request->validate([
            'order_id' => 'required|string',
        ]);

        $url = 'https://sandbox.payhere.lk/merchant/v1/payment/search?order_id=' . $this->request->input('order_id');

        $request = $this->authenticate();

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$request->accessToken}",
        ])->get($url);

        if($response->successful()) {
            $service = Service::findOrFail($this->request->route('id'));

            $subscription = new Subscription();
            $subscription->gate = 'payhere';
            $subscription->user_id = auth()->user()->id;
            $subscription->service_id = $service->id;
            $subscription->payment_status = 1;
            $subscription->transaction_id = $this->request->input('token');
            $subscription->token = $this->request->input('order_id');
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
        } else {
            abort(500, 'Payment Failed');
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

        echo "<form style='visibility: hidden;' name=\"payment\" id=\"payment\" method=\"post\" action=\"https://sandbox.payhere.lk/pay/checkout\" >  
            <input type=\"hidden\" name=\"merchant_id\" value=\"" . config('payment.gateway.payhere.merchant_id') . "\">
            <input type=\"hidden\" name=\"return_url\" value=\"" . route('frontend.payhere.purchase.callback') . "\">
            <input type=\"hidden\" name=\"cancel_url\" value=\"" . route('frontend.payhere.purchase.callback') . "\">
            <input type=\"hidden\" name=\"notify_url\" value=\"" . route('frontend.payhere.purchase.callback') . "\">
            
            <input hidden type=\"text\" name=\"order_id\" value=\"" . generateUuid(16) . "\">
            <input hidden type=\"text\" name=\"items\" value=\"" . implode('|', $items) . "\">
            <input hidden type=\"text\" name=\"currency\" value=\"" . config('settings.currency', 'USD') . "\">
            <input hidden type=\"text\" name=\"amount\" value=\"" . Cart::getTotal() . "\"> 

            <br><br>Customer Details</br></br>
            <input type=\"text\" name=\"first_name\" value=\"" . auth()->user()->name . "\">
            <input type=\"text\" name=\"last_name\" value=\"" . auth()->user()->username . "\">
            <input type=\"text\" name=\"email\" value=\"" . auth()->user()->email . "\">
            <input type=\"text\" name=\"address\" value=\"" . env('APP_NAME') . "\">
            <input type=\"text\" name=\"city\" value=\"" . env('APP_NAME') . "\">
            <input type=\"hidden\" name=\"country\" value=\"" . env('APP_NAME') . "\">
            <input type=\"submit\" value=\"Submit\">
        </form>
        <script type=\"text/javascript\">
            window.onload = function(){
              document.forms['payment'].submit();
            }
        </script>
        ";
        exit;
    }

    public function purchaseCallback()
    {
        Cart::session(auth()->user()->id);

        if(auth()->user()->subscription) {
            abort(403, 'You are already have a subscription.');
        }

        $this->request->validate([
            'order_id' => 'required|string',
        ]);

        $url = 'https://sandbox.payhere.lk/merchant/v1/payment/search?order_id=' . $this->request->input('order_id');

        $request = $this->authenticate();

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$request->accessToken}",
        ])->get($url);

        if($response->successful()) {
            foreach (Cart::getContent() as $item) {
                $order = new Order();
                $order->user_id = auth()->user()->id;
                $order->orderable_id = $item->attributes->orderable_id;
                $order->orderable_type = $item->attributes->orderable_type;
                $order->payment = 'payhere';
                $order->amount = $item->price;
                $order->currency = config('settings.currency', 'USD');
                $order->payment_status = 1;
                $order->transaction_id = $this->request->input('order_id');
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
            abort(500, 'Payment Failed');
        }
    }
}
