<?php

namespace App\Modules\MoMo;

use App\Models\Order;
use App\Models\Service;
use App\Modules\MoMo\MService\Payment\Shared\Constants\Parameter;
use App\Modules\MoMo\MService\Payment\Shared\Utils\Encoder;
use Carbon\Carbon;
use Cart;
use Illuminate\Http\Request;
use App\Models\Email;
use App\Models\Subscription;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use View;
use App\Modules\MoMo\MService\Payment\AllInOne\Processors\CaptureIPN;
use App\Modules\MoMo\MService\Payment\AllInOne\Processors\PayATM;
use App\Modules\MoMo\MService\Payment\AllInOne\Processors\QueryStatusTransaction;
use App\Modules\MoMo\MService\Payment\AllInOne\Processors\RefundATM;
use App\Modules\MoMo\MService\Payment\AllInOne\Processors\RefundMoMo;
use App\Modules\MoMo\MService\Payment\AllInOne\Processors\RefundStatus;
use App\Modules\MoMo\MService\Payment\Shared\SharedModels\Environment;
use App\Modules\MoMo\MService\Payment\Shared\SharedModels\PartnerInfo;
use App\Modules\MoMo\MService\Payment\AllInOne\Processors\CaptureMoMo;
use GuzzleHttp\Client;


class Controller
{
    private $env;
    private $request;

    public function __construct(Request $request, $protocol = 'https')
    {
        $this->request = $request;
        $this->env = new Environment("https://test-payment.momo.vn/gw_payment/transactionProcessor",
            new PartnerInfo("F8BBA842ECF85", 'MOMO', 'K951B6PE1waDMi640xX08PD3vg6EkVlz'),
            'development', '', false);
    }



    public function subscriptionAuthorization()
    {
        if(auth()->user()->subscription) {
            abort(403, 'You are already have a subscription.');
        }

        $orderId = 'MM' . time();
        $requestId = $orderId;
        $capture = CaptureMoMo::process($this->env, $orderId, "Pay With MoMo",  Cart::getTotal(), $orderId, $requestId,  route('frontend.zengapay.subscription.callback', ['id' => $this->request->route('id'), 'order_id' => $orderId]),  route('frontend.zengapay.subscription.callback', ['id' => $this->request->route('id'), 'order_id' => $orderId]));

        return redirect()->to($capture->getPayUrl());
    }

    public function subscriptionCallback()
    {
        if(auth()->user()->subscription) {
            abort(403, 'You are already have a subscription.');
        }

        $order = QueryStatusTransaction::process($this->env, $this->request->input('order_id'), $this->request->input('order_id'));

        if($order->getErrorCode() == 49) {
            die($order->getLocalMessage());
        }

        if($order->status == 'success') {

            $service = Service::findOrFail($this->request->route('id'));
            $subscription = new Subscription();
            $subscription->gate = 'zengapay';
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

        } else {
            die('Payment still on hold, please approve the payment');
        }
    }

    public function purchaseAuthorization()
    {
        Cart::session(auth()->user()->id);

        $orderId = 'MM' . time();
        $requestId = $orderId;
        $capture = CaptureMoMo::process($this->env, $orderId, "Pay With MoMo",  Cart::getTotal(), $orderId, $requestId, route('frontend.momo.purchase.callback', ['order_id' => $orderId]), route('frontend.momo.purchase.callback', ['order_id' => $orderId]));

        return redirect()->to($capture->getPayUrl());
    }

    public function purchaseCallback()
    {
        Cart::session(auth()->user()->id);

        $order = QueryStatusTransaction::process($this->env, $this->request->input('order_id'), $this->request->input('order_id'));

        if($order->getErrorCode() == 49) {
            die($order->getLocalMessage());
        }

        if($order->status = 'success') {
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

        } else {
            die('Payment still on hold, please approve the payment');
        }
    }
}
