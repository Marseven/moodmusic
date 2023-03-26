<?php

namespace App\Modules\TipMe;

use App\Models\Order;
use App\Models\Service;
use Carbon\Carbon;
use Cart;
use Illuminate\Http\Request;
use App\Models\Email;
use App\Models\Subscription;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use View;
use Session;

class Controller
{
    private $endpoint;
    private $authToken;
    private $_APIKey;
    private $_APIVersion;
    private $_resource;
    private $_request;
    /**
     * @var Request
     */
    private $request;

    /**
     * zengaPayPI constructor.
     * @param string $protocol
     * @param Request $request
     */

    public function __construct(Request $request, $protocol = 'https')
    {
        $this->endpoint = config('payment.gateway.tipme.endpoint');
        $this->_protocol = $protocol;
        $this->request = $request;
        $this->_APIKey = config('payment.gateway.tipme.public_key');
        View::addLocation(app_path() . '/Modules/TipMe/views');
    }

    protected function getAuthToken()
    {
        if(! $this->request->session()->get('authToken')) {

            $response = Http::withHeaders([
                'authorizekey' => config('payment.gateway.tipme.api_key')
            ])
                ->asForm()
                ->post($this->endpoint . '/Business_api/authenticate', [
                    "merchant_id" => config('payment.gateway.tipme.merchant_id'),
                ]);

            if (isset($response->object()->data->auth_token)) {
                $this->request->session()->put('authToken', $response->object()->data->auth_token);
                $this->request->session()->save();
                return $response->object()->data->auth_token;
            } else {
                return abort(403, 'Wrong API KEY');
            }
        } else {
            return $this->request->session()->get('authToken');
        }
    }

    public function subscriptionAuthorization()
    {
        if(auth()->user()->subscription) {
            abort(403, 'You are already have a subscription.');
        }

        return View::make('tipme.index')
            ->with('formUrl', route('frontend.tipme.subscription.callback', ['id' => $this->request->route('id')]));
    }

    public function subscriptionCallback()
    {
        if(auth()->user()->subscription) {
            abort(403, 'You are already have a subscription.');
        }

        if($this->request->input('transactionReference')) {
            $request = $this->getSingleCollection($this->request->input('transactionReference'));

            if($request->result->data->transactionStatus === "SUCCEEDED")
            {
                $message = 'SUCCEEDED';
                $service = Service::findOrFail($this->request->route('id'));
                $subscription = new Subscription();
                $subscription->gate = 'tipme';
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
                $message = 'Payment still on hold, please approve the payment';
            }

            return View::make('tipme.verify')
                ->with('transactionReference', $this->request->input('transactionReference'))
                ->with('status', 'failed')
                ->with('message', $message);
        } else {

            $service = Service::findOrFail($this->request->route('id'));
            $request = collect($this->requestPayment($this->request->input('number'), round($service->price) , $service->title,"Pay for Subscription"));

            if(isset($request['result']) && isset($request['result']->code) && $request['result']->code === 202)
            {
                return View::make('tipme.verify')
                    ->with('transactionReference', $request['result']->transactionReference);
            } else {
                return redirect()->route('frontend.tipme.purchase.authorization')->with('status', 'failed')->with('message', $request->first()->{key($request->first())}[0]);
            }
        }
    }

    public function purchaseAuthorization()
    {
        return View::make('tipme.index')
            ->with('formUrl', route('frontend.tipme.purchase.callback'));
    }

    public function purchaseCallback()
    {
        if($this->request->input('otp')) {
            $authToken = $this->getAuthToken();

            $response = Http::withHeaders([
                'authorizekey' => config('payment.gateway.tipme.api_key')
            ])
                ->asForm()
                ->post($this->endpoint . '/Business_api/verify_access_pin', [
                    'merchant_id' => config('payment.gateway.tipme.merchant_id'),
                    'auth_token' => $authToken,
                    'customer_id' => $this->request->input('customer_id'),
                    'amount' => round( Cart::getTotal()),
                    'access_pin' => $this->request->input('otp'),
                    'transation_type' => 'Receive'
                ]);

            if($response->object()->data->status == 1) {
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
                $message = 'Payment still on hold, please approve the payment';
            }

            return View::make('tipme.verify')
                ->with('transactionReference', $this->request->input('transactionReference'))
                ->with('status', 'failed')
                ->with('message', $message);
        } else {
            Cart::session(auth()->user()->id);

            if(Cart::isEmpty()) {
                abort(500,'Cart is empty');
            }

            $items = array();
            foreach (Cart::getContent() as $product) {
                $item = $product->associatedModel->title;
                $items[] = $item;
            }

            $authToken = $this->getAuthToken();

            $response = Http::withHeaders([
                'authorizekey' => config('payment.gateway.tipme.api_key')
            ])
                ->asForm()
                ->post($this->endpoint . '/Business_api/search_customer', [
                    'merchant_id' => config('payment.gateway.tipme.merchant_id'),
                    'auth_token' => $authToken,
                    'customer_id' => $this->request->input('number'),
                ]);


            if($response->object()->data->status == 0) {
                return redirect()->route('frontend.tipme.purchase.authorization')->with('status', 'failed')->with('message', $response->object()->data->msg);
            }

            $response = Http::withHeaders([
                'authorizekey' => config('payment.gateway.tipme.api_key')
            ])
                ->asForm()
                ->post($this->endpoint . '/Business_api/estimate_charges', [
                    'merchant_id' => config('payment.gateway.tipme.merchant_id'),
                    'auth_token' => $authToken,
                    'customer_id' => $this->request->input('number'),
                    'amount' => round( Cart::getTotal()),
                    'currency_code' => config('settings.currency', 'USD'),
                    'charges_by' => 'Receiver',
                    'transation_type' => 'Receive'
                ]);

            if($response->object()->data->status == 0) {
                return redirect()->route('frontend.tipme.purchase.authorization')->with('status', 'failed')->with('message', $response->object()->data->msg);
            } elseif($response->object()->data->status == 1) {
                return View::make('tipme.verify')
                    ->with('customer_id', $this->request->input('number'));
            } else {
                abort(403, 'Can not process payment.');
            }
        }
    }
}
