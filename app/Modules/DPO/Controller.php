<?php

namespace App\Modules\DPO;

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

        $service = Service::findOrFail($this->request->route('id'));

        $amount = $service->price;
        $currency= 'USD';
        $fname= auth()->user()->name;
        $lname= auth()->user()->name;
        $zip= '35353';
        $country= 'US';
        $email= auth()->user()->email;
        $phoneNumber= '039503935';
        $payment= 'CC';
        $random_number=mt_rand(100000,999999);
        $reason= 'Subscription';
        $date= Carbon::now()->format('Y/m/d');

        $xml_data = '<?xml version="1.0" encoding="utf-8"?>'.
            '<API3G>'.
            '<CompanyToken>9F416C11-127B-4DE2-AC7F-D5710E4C5E0A</CompanyToken>'.
            '<Request>createToken</Request>'.
            '<Transaction>'.
            '<PaymentAmount>'.$amount.'</PaymentAmount>'.
            '<PaymentCurrency>'.$currency.'</PaymentCurrency>'.
            '<CompanyRef>'.$random_number.'</CompanyRef>'.
            '<CompanyName>FameMix</CompanyName>'.
            '<CompanyEmail>info@gmail.com</CompanyEmail>'.
            '<RedirectURL>' . route('frontend.dpo.subscription.callback', ['id' => $this->request->route('id')]) . '</RedirectURL>'.
            '<BackURL>' . route('frontend.homepage') . '</BackURL>'.
            '<CompanyRefUnique>0</CompanyRefUnique>'.
            '<PTL>15</PTL>'.
            '<PTLtype>hours</PTLtype>'.
            '<customerFirstName>'.$fname.'</customerFirstName>'.
            '<customerLastName>'.$lname.'</customerLastName>'.
            '<customerZip>'.$zip.'</customerZip>'.
            '<customerCity>'.$country.'</customerCity>'.
            '<customerCountry>RW</customerCountry>'.
            '<customerEmail>'.$email.'</customerEmail>'.
            '<customerNumber>'.$phoneNumber.'</customerNumber>'.
            '<PaymentMethod>'.$payment.'</PaymentMethod>'.
            '</Transaction>'.
            '<Services>'.
            '<Service>'.
            '<ServiceType>5525</ServiceType>'.
            '<ServiceDescription>'.$reason.'</ServiceDescription>'.
            '<ServiceDate>'.$date.'</ServiceDate>'.
            ' </Service>'.
            '</Services>'.
            '</API3G>';


        $ch = curl_init();

        $URL = "https://secure.3gdirectpay.com/API/v6/";

        curl_setopt($ch,CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $xml = simplexml_load_string($output);
        $array=json_encode($xml);
        $array=json_decode($array,true);

        header("Location: https://secure.3gdirectpay.com/dpopayment.php?ID=". $array['TransToken']);
        exit;
    }

    public function subscriptionCallback()
    {
        if(auth()->user()->subscription) {
            abort(403, 'You are already have a subscription.');
        }

        $this->request->validate([
            'TransID' => 'required|string',
        ]);

        $service = Service::findOrFail($this->request->route('id'));

        $subscription = new Subscription();
        $subscription->gate = 'pdo';
        $subscription->user_id = auth()->user()->id;
        $subscription->service_id = $service->id;
        $subscription->payment_status = 1;
        $subscription->transaction_id = $this->request->input('TransID');
        $subscription->token = $this->request->input('TransactionToken');
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

        $amount= Cart::getTotal();
        $currency= 'USD';
        $fname= auth()->user()->name;
        $lname= auth()->user()->name;
        $zip= '35353';
        $country= 'US';
        $email= auth()->user()->email;
        $phoneNumber= '039503935';
        $payment= 'CC';
        $random_number=mt_rand(100000,999999);
        $reason= 'Purchase product';
        $date= Carbon::now()->format('Y/m/d');

        $xml_data = '<?xml version="1.0" encoding="utf-8"?>'.
            '<API3G>'.
            '<CompanyToken>9F416C11-127B-4DE2-AC7F-D5710E4C5E0A</CompanyToken>'.
            '<Request>createToken</Request>'.
            '<Transaction>'.
            '<PaymentAmount>'.$amount.'</PaymentAmount>'.
            '<PaymentCurrency>'.$currency.'</PaymentCurrency>'.
            '<CompanyRef>'.$random_number.'</CompanyRef>'.
            '<CompanyName>FameMix</CompanyName>'.
            '<CompanyEmail>info@gmail.com</CompanyEmail>'.
            '<RedirectURL>' . route('frontend.dpo.purchase.callback') . '</RedirectURL>'.
            '<BackURL>' . route('frontend.homepage') . '</BackURL>'.
            '<CompanyRefUnique>0</CompanyRefUnique>'.
            '<PTL>15</PTL>'.
            '<PTLtype>hours</PTLtype>'.
            '<customerFirstName>'.$fname.'</customerFirstName>'.
            '<customerLastName>'.$lname.'</customerLastName>'.
            '<customerZip>'.$zip.'</customerZip>'.
            '<customerCity>'.$country.'</customerCity>'.
            '<customerCountry>RW</customerCountry>'.
            '<customerEmail>'.$email.'</customerEmail>'.
            '<customerNumber>'.$phoneNumber.'</customerNumber>'.
            '<PaymentMethod>'.$payment.'</PaymentMethod>'.
            '</Transaction>'.
            '<Services>'.
            '<Service>'.
            '<ServiceType>5525</ServiceType>'.
            '<ServiceDescription>'.$reason.'</ServiceDescription>'.
            '<ServiceDate>'.$date.'</ServiceDate>'.
            ' </Service>'.
            '</Services>'.
            '</API3G>';

        $ch = curl_init();


        $URL = "https://secure.3gdirectpay.com/API/v6/";

        curl_setopt($ch,CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $xml = simplexml_load_string($output);
        $array=json_encode($xml);
        $array=json_decode($array,true);

        header("Location: https://secure.3gdirectpay.com/dpopayment.php?ID=". $array['TransToken']);
        exit;
    }

    public function purchaseCallback()
    {
        $this->request->validate([
            'TransID' => 'required|string',
        ]);

        Cart::session(auth()->user()->id);

        foreach (Cart::getContent() as $item) {
            $order = new Order();
            $order->user_id = auth()->user()->id;
            $order->orderable_id = $item->attributes->orderable_id;
            $order->orderable_type = $item->attributes->orderable_type;
            $order->payment = 'pdo';
            $order->amount = $item->price;
            $order->currency = config('settings.currency', 'USD');
            $order->payment_status = 1;
            $order->transaction_id = $this->request->input('TransID');
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
