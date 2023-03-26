<?php

namespace App\Modules\Mpesa;

use App\Models\Order;
use App\Models\Service;
use App\Modules\Mpesa\Config;
use App\Modules\Mpesa\Transaction;
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
        $this->request = $request;
        View::addLocation(app_path() . '/Modules/Mpesa/views');
    }

    public function subscriptionAuthorization()
    {
        if(auth()->user()->subscription) {
            abort(403, 'You are already have a subscription.');
        }

        return View::make('mpesa.index')
            ->with('formUrl', route('frontend.mpesa.subscription.callback', ['id' => $this->request->route('id')]));
    }

    public function subscriptionCallback()
    {
        if(auth()->user()->subscription) {
            abort(403, 'You are already have a subscription.');
        }

        if($this->request->input('transactionReference')) {
            $transactionReference = $this->request->input('transactionReference');
            $config = new Config();
            $transaction = new Transaction($config);
            $t = $transaction->query($transactionReference, config('payment.gateway.mpesa.service_provider_code'));

            if($t->getCode() == 'INS-0') {
                $service = Service::findOrFail($this->request->route('id'));
                $subscription = new Subscription();
                $subscription->gate = 'mpesa';
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

            return View::make('mpesa.verify')
                ->with('transactionReference', $this->request->input('transactionReference'))
                ->with('status', 'failed')
                ->with('message', $message);
        } else {

            $service = Service::findOrFail($this->request->route('id'));
            $config = new Config();
            $transaction = new Transaction($config);

            $c2b = $transaction->c2b(
                round($service->price),
                $this->request->input('number'),
                time(),
                config('payment.gateway.mpesa.service_provider_code')
            );

            if($c2b->getTransactionID() != 'N/A')
            {
                return View::make('mpesa.verify')
                    ->with('transactionReference', $c2b->getTransactionID())
                    ->with('conversationReference', $c2b->getConversationID());
            } else {
                return redirect()->route('frontend.mpesa.subscription.callback', ['id' => $this->request->route('id')])->with('status', 'failed')->with('message', $c2b->getDescription());
            }
        }
    }

    public function purchaseAuthorization()
    {
        return View::make('mpesa.index')
            ->with('formUrl', route('frontend.mpesa.purchase.callback'));
    }

    public function purchaseCallback()
    {
        if($this->request->input('transactionReference')) {
            $transactionReference = $this->request->input('transactionReference');

            $config = new Config();
            $transaction = new Transaction($config);
            $t = $transaction->query($transactionReference, config('payment.gateway.mpesa.service_provider_code'));

            if($t->getCode() == 'INS-0') {

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

            return View::make('mpesa.verify')
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

            $config = new Config();
            $transaction = new Transaction($config);

            $c2b = $transaction->c2b(
                round(Cart::getTotal()),
                $this->request->input('number'),
                time(),
                config('payment.gateway.mpesa.service_provider_code')
            );

            if($c2b->getTransactionID() != 'N/A')
            {
                return View::make('mpesa.verify')
                    ->with('transactionReference', $c2b->getTransactionID())
                    ->with('conversationReference', $c2b->getConversationID());
            } else {
                return redirect()->route('frontend.mpesa.purchase.authorization')->with('status', 'failed')->with('message', $c2b->getDescription());
            }
        }
    }
}
