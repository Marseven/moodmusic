<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-06
 * Time: 23:14
 */

namespace App\Http\Controllers\Backend;

use App\Models\Email;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Models\Subscription;
use PayPal\Api\Agreement;
use Stripe\StripeClient;

class SubscriptionsController
{
    private $request;
    private $select;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $subscriptions = Subscription::withoutGlobalScopes()->orderBy('id', 'desc');


        if ($this->request->has('term'))
        {
            $term = $this->request->input('term');
            $subscriptions = $subscriptions->whereHas('user', function ($query) use ($term){
                $query->where('name', 'like', '%' . $term . '%');
            });
        }

        $subscriptions = $subscriptions->paginate(20);

        $total = DB::table('subscriptions')->count();

        return view('backend.subscriptions.index')
            ->with('total', $total)
            ->with('term', $this->request->input('term'))
            ->with('subscriptions', $subscriptions);
    }

    public function edit()
    {
        $order = Subscription::findOrFail($this->request->route('id'));

        return view('backend.subscriptions.form')
            ->with('order', $order);
    }
}