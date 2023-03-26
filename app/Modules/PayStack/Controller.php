<?php

namespace App\Modules\PayStack;

use App\Models\Order;
use App\Models\Service;
use Carbon\Carbon;
use Cart;
use Illuminate\Http\Request;
use App\Models\Email;
use App\Models\Subscription;

class Controller
{
    private $request;

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

        $curl = curl_init();
        $email = auth()->user()->email;
        $amount = $service->price * 100;
        $callback_url = route('frontend.paystack.subscription.callback', ['id' => $this->request->route('id')]);

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'amount'=>$amount,
                'email'=>$email,
                'callback_url' => $callback_url
            ]),
            CURLOPT_HTTPHEADER => [
                "authorization: Bearer " . config('payment.gateway.paystack.secret_key'),
                "content-type: application/json",
                "cache-control: no-cache"
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err){
            abort(500, 'Curl returned error: ' . $err);
        }

        $tranx = json_decode($response, true);

        if(!$tranx['status']){
            abort(500, 'API returned error: ' . $tranx['message']);
        }

        header('Location: ' . $tranx['data']['authorization_url']);
        exit;
    }

    public function subscriptionCallback()
    {
        $this->request->validate([
            'reference' => 'required|string',
        ]);

        $curl = curl_init();
        $reference = $this->request->input('reference');

        if(!$reference){
            abort(500, 'No reference supplied');
        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "authorization: Bearer " . config('payment.gateway.paystack.secret_key'),
                "cache-control: no-cache"
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err){
            // there was an error contacting the Paystack API
            abort(500, 'Curl returned error: ' . $err);
        }

        $tranx = json_decode($response);

        if(!$tranx->status){
            // there was an error from the API
            abort(500, 'API returned error: ' . $tranx->message);
        }

        if($tranx->data->status == 'success'){
            $service = Service::findOrFail($this->request->route('id'));

            $subscription = new Subscription();

            $subscription->gate = 'paypal';
            $subscription->user_id = auth()->user()->id;
            $subscription->service_id = $service->id;
            $subscription->payment_status = 1;
            $subscription->transaction_id = $this->request->input('token');
            $subscription->token = $tranx->data->reference;
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


            exit();
        }
    }

    public function purchaseAuthorization()
    {
        Cart::session(auth()->user()->id);

        if(Cart::isEmpty()) {
            abort(500,'Cart is empty');
        }

        $curl = curl_init();
        $email = auth()->user()->email;
        $amount = Cart::getTotal() * 100;
        $callback_url = route('frontend.paystack.purchase.callback');

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'amount'=>$amount,
                'email'=>$email,
                'callback_url' => $callback_url
            ]),
            CURLOPT_HTTPHEADER => [
                "authorization: Bearer " . config('payment.gateway.paystack.secret_key'),
                "content-type: application/json",
                "cache-control: no-cache"
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err){
            abort(500, 'Curl returned error: ' . $err);
        }

        $tranx = json_decode($response, true);

        if(!$tranx['status']){
            abort(500, 'API returned error: ' . $tranx['message']);
        }

        header('Location: ' . $tranx['data']['authorization_url']);
        exit;
    }

    public function purchaseCallback()
    {
        Cart::session(auth()->user()->id);
        
        $this->request->validate([
            'reference' => 'required|string',
        ]);

        $curl = curl_init();
        $reference = $this->request->input('reference');

        if(!$reference){
            abort(500, 'No reference supplied');
        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "authorization: Bearer " . config('payment.gateway.paystack.secret_key'),
                "cache-control: no-cache"
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err){
            // there was an error contacting the Paystack API
            abort(500, 'Curl returned error: ' . $err);
        }

        $tranx = json_decode($response);

        if(!$tranx->status){
            // there was an error from the API
            abort(500, 'API returned error: ' . $tranx->message);
        }

        if($tranx->data->status == 'success'){
            foreach (Cart::getContent() as $item) {
                $order = new Order();
                $order->user_id = auth()->user()->id;
                $order->orderable_id = $item->attributes->orderable_id;
                $order->orderable_type = $item->attributes->orderable_type;
                $order->payment = 'paystack';
                $order->amount = $item->price;
                $order->currency = config('settings.currency', 'USD');
                $order->payment_status = 1;
                $order->transaction_id = $tranx->data->reference;
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
}
