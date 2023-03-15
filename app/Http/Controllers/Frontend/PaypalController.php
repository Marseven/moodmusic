<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-06
 * Time: 17:06
 */

namespace App\Http\Controllers\Frontend;

use App\Models\Email;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Service;
use DB;
use Auth;
use Cart;
use App\Models\User;
use Carbon\Carbon;
use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;
use PayPal\Api\Agreement;
use PayPal\Api\AgreementStateDescriptor;
use PayPal\Api\Payer;
use PayPal\Api\PayerInfo;
use PayPal\Api\ShippingAddress;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\Authorization;
use PayPal\Api\Capture;
use PayPal\Api\PaymentExecution;

use PHPUnit\TextUI\ResultPrinter;
use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;
use App\Modules\MobileHelper\APISession;

class PaypalController extends APISession
{
    private $apiContext;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                env('PAYPAL_APP_CLIENT_ID'),
                env('PAYPAL_APP_SECRET')
            )
        );

        if(! config('settings.payment_paypal_sandbox')) {
            $this->apiContext->setConfig(
                array(
                    'mode' => 'live'
                )
            );
        }

        $this->apiSession();
    }

    public function subscription()
    {
        if(auth()->user()->subscription) {
            abort(403, 'You are already have a subscription.');
        }

        $service = Service::findOrFail($this->request->route('id'));

        $plan = new Plan();

        $plan->setName($service->title)
            ->setDescription($service->price . $service->plan_period)
            ->setType('infinite');

        $paymentDefinition = new PaymentDefinition();

        if(in_array(config('settings.currency', 'USD'), config('payment.paypal_currency_subscription'))) {
            $amount = in_array(config('settings.currency', 'USD'), config('payment.currency_decimals')) ? $service->price : intval($service->price);
        } else {
            $exchangeRates = new ExchangeRate();
            $amount = number_format($exchangeRates->convert($service->price, config('settings.currency'), 'USD', Carbon::now()), 2);
        }

        switch ($service->plan_period_format) {
            case 'D':
                $frequency = 'Day';
                break;
            case 'W':
                $frequency = 'Week';
                break;
            case 'M':
                $frequency = 'Month';
                break;
            case 'Y':
                $frequency = 'Year';
                break;
            default:
                $frequency = 'Month';
        }

        $paymentDefinition->setName('Regular Payments')
            ->setType('REGULAR')
            ->setFrequency($frequency)
            ->setFrequencyInterval(intval($service->plan_period))
            ->setAmount(new Currency(array('value' => $amount, 'currency' => config('settings.currency', 'USD'))));

        $service->title = $service->title . ' ' . config('settings.currency') . $amount . '/' . $frequency;

        $merchantPreferences = new MerchantPreferences();

        $merchantPreferences->setReturnUrl(route('frontend.paypal.subscription.success', ['id' => $service->id]))
            ->setCancelUrl(route('frontend.paypal.subscription.cancel', ['id' => $service->id]))
            ->setAutoBillAmount("yes")
            ->setInitialFailAmountAction("CONTINUE")
            ->setMaxFailAttempts("0");

        $plan->setPaymentDefinitions(array($paymentDefinition));
        $plan->setMerchantPreferences($merchantPreferences);


        try {

            $createdPlan = $plan->create($this->apiContext);

            try {

                $patch = new Patch();
                $value = new PayPalModel('{"state":"ACTIVE"}');
                $patch->setOp('replace')
                    ->setPath('/')
                    ->setValue($value);

                $patchRequest = new PatchRequest();
                $patchRequest->addPatch($patch);

                $createdPlan->update($patchRequest, $this->apiContext);
                $createdPlan = Plan::get($createdPlan->getId(), $this->apiContext);
                $agreement = new Agreement();

                if($service->trial) {
                    switch ($service->trial_period_format) {
                        case 'D':
                            $trial_end = Carbon::now()->addDays($service->trial_period)->format('Y-m-d\TH:i:s\Z');
                            break;
                        case 'W':
                            $trial_end = Carbon::now()->addWeeks($service->trial_period)->format('Y-m-d\TH:i:s\Z');
                            break;
                        case 'M':
                            $trial_end = Carbon::now()->addMonths($service->trial_period)->format('Y-m-d\TH:i:s\Z');
                            break;
                        case 'Y':
                            $trial_end = Carbon::now()->addYears($service->trial_period)->format('Y-m-d\TH:i:s\Z');
                            break;
                        default:
                            $trial_end = date("Y-m-d\TH:i:s\Z", strtotime('+2 minute'));
                    }
                } else {
                    $trial_end = date("Y-m-d\TH:i:s\Z", strtotime('+2 minute'));;
                }

                $agreement->setName($service->title . ' ' . config('settings.currency') . $amount . '/' . $frequency)
                    ->setDescription($service->title)
                    ->setStartDate($trial_end);

                $plan = new Plan();
                $plan->setId($createdPlan->getId());
                $agreement->setPlan($plan);
                $payer = new Payer();
                $payer->setPaymentMethod('paypal');
                $agreement->setPayer($payer);

                try {
                    $agreement = $agreement->create($this->apiContext);
                    $approvalUrl = $agreement->getApprovalLink();
                } catch (Exception $ex) {
                    echo "Failed to get activate";
                    var_dump($ex);
                    exit();
                }

                header("Location:" . $approvalUrl);
                exit();

            } catch (Exception $ex) {
                echo "Failed to get activate";
                var_dump($ex);
                exit();
            }

        } catch (Exception $ex) {
            echo "Failed to get activate";
            var_dump($ex);
            exit();
        }

    }

    public function success() {
        if(auth()->user()->subscription) {
            abort(403, 'You are already have a subscription.');
        }

        $this->request->validate([
            'token' => 'required|string',
        ]);

        $service = Service::findOrFail($this->request->route('id'));

        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                env('PAYPAL_APP_CLIENT_ID'),
                env('PAYPAL_APP_SECRET')
            )
        );

        $token = $this->request->input('token');
        $agreement = new \PayPal\Api\Agreement();

        try {
            $agreement->execute($token, $apiContext);
            $subscription = new Subscription();

            $subscription->gate = 'paypal';
            $subscription->user_id = auth()->user()->id;
            $subscription->service_id = $service->id;
            $subscription->payment_status = 1;
            $subscription->transaction_id = $this->request->input('token');
            $agreement = Agreement::get($agreement->getId(), $apiContext);
            $subscription->token = $agreement->getId();
            $plan = $agreement->getPlan();
            $subscription->trial_end = Carbon::parse($agreement->getAgreementDetails()->next_billing_date);
            $subscription->next_billing_date = Carbon::parse($agreement->getAgreementDetails()->next_billing_date);
            $subscription->cycles = $agreement->getAgreementDetails()->cycles_completed;
            $subscription->amount = $plan->getPaymentDefinitions()[0]->amount->value;
            $subscription->currency = $plan->getPaymentDefinitions()[0]->amount->currency;


            if(! $service->trial) {
                $subscription->last_payment_date = Carbon::now();
            }

            $subscription->save();

            (new Email)->subscriptionReceipt(auth()->user(), $subscription);

            echo '<script type="text/javascript">
            try {
                var opener = window.opener;
                if(opener) {
                    opener.Payment.subscriptionSuccess();
                    window.close();
                }
                
            } catch(e) {
                
            }
            setTimeout(function () {
                window.location.href = "' . config('settings.deeplink_scheme', 'musicengine') . '://engine/payment/success";
            }, 1000);
            
            </script>';
        } catch (Exception $ex) {
            echo "Failed to get activate";
            var_dump($ex);
            exit();
        }
    }

    public function purchase()
    {
        Cart::session(auth()->user()->id);

        if(Cart::isEmpty()) {
            abort(500,'Cart is empty');
        }

        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                env('PAYPAL_APP_CLIENT_ID'),
                env('PAYPAL_APP_SECRET')
            )
        );

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $items = array();

        foreach (Cart::getContent() as $product) {
            $item = new Item();
            $item->setName($product->associatedModel->title)
                ->setCurrency(config('settings.currency', 'USD'))
                ->setQuantity(1)
                ->setPrice($product->price);
            $items[] = $item;
        }

        $itemList = new ItemList();
        $itemList->setItems($items);

        $details = new Details();
        /*$details->setShipping(1.2)
            ->setTax(1.3)
            ->setSubtotal(17.50);*/
        $amount = new Amount();
        $amount->setCurrency(config('settings.currency', 'USD'))
            ->setTotal(Cart::getTotal())
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Ordering media")
            ->setInvoiceNumber(uniqid());

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($this->request->is('api*') ? route('api.paypal.purchase.authorization.success', ['api-token' => $this->request->input('api-token')]) : route('frontend.paypal.purchase.authorization.success'))
            ->setCancelUrl($this->request->is('api*') ? route('api.paypal.purchase.authorization.cancel', ['api-token' => $this->request->input('api-token')]) : route('frontend.paypal.purchase.authorization.cancel'));

        $payment = new Payment();
        $payment->setIntent("authorize")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));
        $request = clone $payment;
        try {
            $payment->create($apiContext);
        } catch (Exception $ex) {
            abort(403, "Problem with creating Payment Authorization Using PayPal");
        }

        $approvalUrl = $payment->getApprovalLink();

        header('Location: ' . $approvalUrl);
        exit;
    }

    public function successAuthorization() {
        Cart::session(auth()->user()->id);

        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                env('PAYPAL_APP_CLIENT_ID'),
                env('PAYPAL_APP_SECRET')
            )
        );

        $paymentId = $this->request->input('paymentId');
        $payment = Payment::get($paymentId, $apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($this->request->input('PayerID'));
        $transaction = new Transaction();
        $amount = new Amount();
        $details = new Details();
        $amount->setCurrency(config('settings.currency', 'USD'));
        $amount->setTotal(Cart::getTotal());
        $amount->setDetails($details);
        $transaction->setAmount($amount);
        $execution->addTransaction($transaction);
        try {
            $result = $payment->execute($execution, $apiContext);

            try {
                $authorizationId = $result->transactions[0]->related_resources[0]->authorization->id;
                $authorization = Authorization::get($authorizationId, $apiContext);
                $amt = new Amount();
                $amt->setCurrency(config('settings.currency', 'USD'))
                    ->setTotal(Cart::getTotal());
                $capture = new Capture();
                $capture->setAmount($amt);
                $getCapture = $authorization->capture($capture, $apiContext);

                foreach (Cart::getContent() as $item) {
                    $order = new Order();
                    $order->user_id = auth()->user()->id;
                    $order->orderable_id = $item->attributes->orderable_id;
                    $order->orderable_type = $item->attributes->orderable_type;
                    $order->payment = 'paypal';
                    $order->amount = $item->price;
                    $order->currency = config('settings.currency', 'USD');
                    $order->payment_status = 1;
                    $order->transaction_id = $paymentId;
                    $order->save();
                }

                Cart::clear();

                echo '<script type="text/javascript">
                        try {
                            var opener = window.opener;
                            if(opener) {
                                opener.Payment.purchaseSuccess();
                                window.close();
                            }
                        } catch(e) {
                            
                        }
                        
                        setTimeout(function () {
                            window.location.href = "' . config('settings.deeplink_scheme', 'musicengine') . '://engine/payment/success";
                        }, 1000);
            
                      </script>';
                exit;
            } catch (Exception $ex) {
                abort(403, "Can't capture the authorization payment with paypal");
            }
        } catch (Exception $ex) {
            abort(403, "Can't execute payment with paypal");
        }
    }

    public function cancel() {
        $view = view()->make('commons.abort-with-message')->with('code', 'Cancel')->with('message', 'Payment canceled by customer.');
        die($view);
    }
}