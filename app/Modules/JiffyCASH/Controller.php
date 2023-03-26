<?php

namespace App\Modules\JiffyCASH;

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

        echo "<!DOCTYPE html>
    <html>
    <link href=\"" . asset('backend/css/style.css') . "\" rel=\"stylesheet\">
	<body>
	<div class='container d-flex justify-content-center align-items-center mt-5'>
	<div class=\"card\" style='min-width: 350px; max-width: 400px'>
  <div class=\"card-header\">
    JiffyCASH
  </div>
  <div class=\"card-body\">
    <form method='post' action='" . route('frontend.jiffycash.subscription.callback', ['id' => $this->request->route('id')]) .  "'>
    " . csrf_field() . "
  <div class=\"form-group\">
    <label for=\"cardNumber\">Card Number</label>
    <input type=\"text\" name=\"cardNumber\" class=\"form-control\" id=\"form-group\" placeholder=\"Enter card number\" required>
  </div>
  <button type=\"submit\" class=\"btn btn-primary\">Submit</button>
</form>

  </div>
</div>
	</div>
</form>
  </body>
</html>";

        exit;
    }

    public function subscriptionCallback()
    {
        $this->request->validate([
            'cardNumber' => 'required|string',
        ]);

        $response = Http::post('https://jiffypay.app/v2/api/payment/redeem_voucher_code', [
            "username" => config('payment.gateway.jiffyCASH.username'),
            "code" => $this->request->input('cardNumber')
        ]);

        if ($response->successful() && $response->object()->status == 'success') {

            $service = Service::findOrFail($this->request->route('id'));

            $subscription = new Subscription();

            $subscription->gate = 'paypal';
            $subscription->user_id = auth()->user()->id;
            $subscription->service_id = $service->id;
            $subscription->payment_status = 1;
            $subscription->transaction_id = $this->request->input('token');
            $subscription->token = $response->object()->trans_id;
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
        } else {
            abort(403, 'Payment failure. Something went wrong, please contact the administrator.');
        }
    }

    public function purchaseAuthorization()
    {
        echo "<!DOCTYPE html>
    <html>
    <link href=\"" . asset('backend/css/style.css') . "\" rel=\"stylesheet\">
    <style>
    body {
        background: black;
    }
    .card-header {
    }
	.card {
    }
    button {
       background: #F0D68B !important;
       border-color: #F0D68B !important;
       color: black !important;
    }
	</style>
	<body>
	<div class='container d-flex justify-content-center align-items-center mt-5'>
	<div class=\"card\" style='min-width: 350px; max-width: 400px'>
  <div class=\"card-header d-flex justify-content-center align-items-center\" style='background:black'>
    <img src=''/>
  </div>
  <div class=\"card-body\">
    <form method='post' action='" . route('frontend.jiffycash.purchase.callback') .  "'>
    " . csrf_field() . "
  <div class=\"form-group\">
    <label for=\"cardNumber\">JiffyCASH CODE</label>
    <input type=\"text\" name=\"cardNumber\" class=\"form-control\" id=\"form-group\" placeholder=\"ENTER JiffyCASH CODE\" required>
  </div>
  <button type=\"submit\" class=\"btn btn-primary\">Submit</button>
</form>

  </div>
</div>
	</div>
</form>
  </body>
</html>";
        exit;
    }

    public function purchaseCallback()
    {
        Cart::session(auth()->user()->id);

        $this->request->validate([
            'cardNumber' => 'required|string',
        ]);

        $response = Http::post('https://jiffypay.app/v2/api/payment/redeem_voucher_code', [
            "username" => config('payment.gateway.jiffyCASH.username'),
            "code" => $this->request->input('cardNumber')
        ]);

        if ($response->successful() && $response->object()->status == 'success') {
            foreach (Cart::getContent() as $item) {
                $order = new Order();
                $order->user_id = auth()->user()->id;
                $order->orderable_id = $item->attributes->orderable_id;
                $order->orderable_type = $item->attributes->orderable_type;
                $order->payment = 'paystack';
                $order->amount = $item->price;
                $order->currency = config('settings.currency', 'USD');
                $order->payment_status = 1;
                $order->transaction_id = $response->object()->trans_id;;
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
            abort(403, 'Payment failure. Something went wrong, please contact the administrator.');
        }
    }
}
