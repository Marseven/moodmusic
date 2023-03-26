<?php

namespace App\Modules\Zengapay;

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

class Controller
{
    private $_host;
    private $_protocol;
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
        $this->_host = config('payment.gateway.zengapay.endpoint');
        $this->_APIVersion = '/v1';
        $this->_protocol = $protocol;
        $this->request = $request;
        $this->_APIKey = config('payment.gateway.zengapay.public_key');
        View::addLocation(app_path() . '/Modules/Zengapay/views');
    }
    /**
     * Request Funds from a Mobile Money User, The requested funds will be deposited into your account
     * Shortly after you submit this request, the mobile money user receives an on-screen
     * notification on their mobile phone. The notification informs the mobile money user about
     * your request to transfer funds out of their account and requests them to authorize the
     * request to complete the transaction.
     * This request is not supported by all mobile money operator networks
     * @param string $msisdn: The mobile money phone number in the format 256772123456
     * @param double $amount: The amount of money to be deposited into your account (floats are supported)
     * @param string $external_reference: Something which yourself and the beneficiary agree upon e.g. an invoice number
     * @param string $narration: The reason for the mobile money user to deposit funds
     * @return array
     */

    public function requestPayment($msisdn,$amount,$external_reference,$narration)
    {
        $this->_resource = "/collections";
        $this->_request = array(
            "msisdn"=>$msisdn,
            "amount"=>$this->unformat($amount),
            "external_reference"=>$external_reference,
            "narration"=>$narration
        );
        return $this->sendAPIRequest('POST',$this->_resource,json_encode($this->_request));
    }
    /**
     * Check the status of a single transaction that was earlier submitted for processing.
     * It can also be used to check on any other transaction on the system.
     * @param string $transactionReference: The reference to the transaction whose status you would like to follow up on. This is typically the transaction reference which came through as part of an earlier collection request response.
     * @return object
     */

    public function getSingleCollection($transactionReference)
    {
        return $this->sendAPIRequest('GET',"/collections/{$transactionReference}");
    }
    /**
     * Fetch all collections that were earlier submitted for processing.
     * @return object
     */

    public function getAllCollections()
    {
        return $this->sendAPIRequest('GET',"/collections");
    }
    /**
     * Transfer funds from your ZENGAPAY Account to a mobile money user
     * This transaction transfers funds from your ZENGAPAY Account to a mobile money user.
     * Please handle this request with care because if compromised, it can lead to
     * withdrawal of funds from your account.
     * This request is not supported by all mobile money operator networks
     * This request requires permission that is granted by the specific IP Address(es) whitelisted in your ZENGAPAY Dashboard
     * @param string $msisdn the mobile money phone number in the format 256772123456
     * @param double $amount: The amount of money to withdraw from your account (floats are supported)
     * @param string $external_reference: Something which yourself and the beneficiary agree upon e.g. an invoice number
     * @param string $narration: The reason for the mobile money user to deposit funds
     * @return array
     */

    public function sendTransfer($msisdn,$amount,$external_reference,$narration)
    {
        $this->_resource = "/transfers";
        $this->_request = array(
            "msisdn"=>$msisdn,
            "amount"=>$this->unformat($amount),
            "external_reference"=>$external_reference,
            "narration"=>$narration
        );
        return $this->sendAPIRequest('POST',$this->_resource,json_encode($this->_request));
    }
    /**
     * Check the status of a single transaction that was earlier submitted for processing.
     * It can also be used to check on any other transaction on the system.
     * @param string $transactionReference: The reference to the transaction whose status you would like to follow up on. This is typically the transaction reference which came through as part of an earlier transfer request response.
     * @return object
     */

    public function getSingleTransfer($transactionReference)
    {
        return $this->sendAPIRequest('GET',"/transfers/{$transactionReference}");
    }
    /**
     * Fetch all transfers that were earlier submitted for processing.
     * @return object
     */

    public function getAllTransfers()
    {
        return $this->sendAPIRequest('GET',"/transfers");
    }
    /**
     * Get the current balance of your ZENGAPAY Account
     * Returns objects contains an array of balances (including airtime)
     * @return object
     */

    public function accountGetBalance()
    {
        return $this->sendAPIRequest('GET',"/account/balance");
    }
    /**
     * Return an account statement object of transactions which were carried out on your account for a certain period of time
     * @param string $start format YYYY-MM-DD HH:MM:SS
     * @param string $end  format YYYY-MM-DD HH:MM:SS
     * @param string $status
     * Options
     * * "FAILED"
     * * "PENDING"
     * * "INDETERMINATE"
     * * "SUCCEEDED"
     * * "FAILED,SUCCEEDED" (comma separated)
     * @param string $currency_code
     * Options
     * * "UGX-MTNMM" -> Uganda Shillings - MTN Mobile Money
     * * "UGX-ATLMM" -> Uganda Shillings - Airtel Money
     * @param int $limit Default limit = 25
     * @param string $designation
     * Options
     * * "TRANSACTION"
     * * "CHARGES"
     * * "ANY"
     * @return object
     */

    public function accountGetStatement($start=NULL, $end=NULL, $status=NULL, $currency_code=NULL, $limit=NULL, $designation='ANY')
    {
        return $this->sendAPIRequest('GET',"/account/statement");
    }
    /**
     * Define API key for authentication
     *
     * @param string $APIKey
     */

    public function setAPIKey($APIKey)
    {

    }

    /**
     * Clean Amount before passing to API
     * @param string $number
     * @param bool $force_number
     * @param string $dec_point
     * @param string $thousands_sep
     * @return int or float
     */

    private function unformat($number, $force_number = true, $dec_point = '.', $thousands_sep = ',') {
        if ($force_number) {
            $number = preg_replace('/^[^\d]+/', '', $number);
        } else if (preg_match('/^[^\d]+/', $number)) {
            return false;
        }
        $type = (strpos($number, $dec_point) === false) ? 'int' : 'float';
        $number = str_replace(array($dec_point, $thousands_sep), array('.', ''), $number);
        settype($number, $type);
        return $number;
    }

    /**
     * Retrieve and set a list of headers needed for request
     *
     * @return array
     */

    private function _setHeaders()
    {
        $headers = array(
            "Content-Type:application/json",
        );
        if ($this->_APIKey)
        {
            $headers[] = "Authorization: Bearer {$this->_APIKey}";
        }
        return $headers;
    }

    /**
     * Perform API request
     *
     * @param string $method
     * @param string $endPoint
     * @param string $body
     * @return object
     */

    protected function sendAPIRequest($method,$endPoint,$body=NULL)
    {
        $ch = curl_init( );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->_setHeaders());
        curl_setopt($ch, CURLOPT_URL, "{$this->_protocol}://{$this->_host}{$this->_APIVersion}{$endPoint}");
        $method == "POST" ? curl_setopt($ch, CURLOPT_POST, true) : curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        isset($body) ? curl_setopt($ch, CURLOPT_POSTFIELDS, $body) :'';
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30 );
        $output = new \stdClass();
        $output->result = json_decode(curl_exec($ch));
        $curl_info = curl_getinfo($ch);
        $output->httpResponseCode = $curl_info['http_code'];
        curl_close($ch);
        //dd("{$this->_protocol}://{$this->_host}{$this->_APIVersion}{$endPoint}");
        return $output;
    }



    public function subscriptionAuthorization()
    {
        if(auth()->user()->subscription) {
            abort(403, 'You are already have a subscription.');
        }

        return View::make('zengapay.index')
            ->with('formUrl', route('frontend.zengapay.subscription.callback', ['id' => $this->request->route('id')]));
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
                $message = 'Payment still on hold, please approve the payment';
            }

            return View::make('zengapay.verify')
                ->with('transactionReference', $this->request->input('transactionReference'))
                ->with('status', 'failed')
                ->with('message', $message);
        } else {

            $service = Service::findOrFail($this->request->route('id'));
            $request = collect($this->requestPayment($this->request->input('number'), round($service->price) , $service->title,"Pay for Subscription"));

            if(isset($request['result']) && isset($request['result']->code) && $request['result']->code === 202)
            {
                return View::make('zengapay.verify')
                    ->with('transactionReference', $request['result']->transactionReference);
            } else {
                return redirect()->route('frontend.zengapay.subscription.callback', ['id' => $this->request->route('id')])->with('status', 'failed')->with('message', $request->first()->{key($request->first())}[0]);
            }
        }
    }

    public function purchaseAuthorization()
    {
        return View::make('zengapay.index')
            ->with('formUrl', route('frontend.zengapay.purchase.callback'));
    }

    public function purchaseCallback()
    {
        if($this->request->input('transactionReference')) {
            $request = $this->getSingleCollection($this->request->input('transactionReference'));

            try {
                if($request->result->data->transactionStatus === "SUCCEEDED")
                {
                    $message = 'SUCCEEDED';

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
            } catch (\Exception $e) {
                dd($request);
            }

            return View::make('zengapay.verify')
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

            $request = collect($this->requestPayment($this->request->input('number'), Cart::getTotal(), time(), 'Payment for ' . Cart::getContent()->count() . ' item(s) (Order '.time().'). '));

            if(isset($request['result']) && isset($request['result']->code) && $request['result']->code === 202)
            {
                return View::make('zengapay.verify')
                    ->with('transactionReference', $request['result']->transactionReference);
            } else {
                return redirect()->route('frontend.zengapay.purchase.authorization')->with('status', 'failed')->with('message', $request->first()->{key($request->first())}[0]);
            }
        }
    }
}
