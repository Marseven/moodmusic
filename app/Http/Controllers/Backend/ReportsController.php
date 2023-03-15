<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-08
 * Time: 21:50
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use DB;
use View;
use Carbon\Carbon;

class ReportsController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        /** Get subscriptions charts */

        $fromDate = Carbon::now()->subMonth(1)->format('Y/m/d H:i:s');
        $toDate = Carbon::now()->format('Y/m/d H:i:s');

        $data = $this->getData($fromDate, $toDate);

        return view('backend.reports.index')
            ->with('day', $data->day)
            ->with('month', $data->month)
            ->with('plans', $data->plans)
            ->with('data', $data);

    }

    public function post(){
        $fromDate = Carbon::parse(($this->request->input('from')))->format('Y/m/d H:i:s');
        $toDate = Carbon::parse(($this->request->input('to')))->format('Y/m/d H:i:s');
        if(strtotime($fromDate) > strtotime($toDate)) {
            return redirect()->route('backend.reports')->with('status', 'failed')->with('message', 'From date should not be bigger then To date.');

        }

        $data = $this->getData($fromDate, $toDate);

        return view('backend.reports.index')
            ->with('day', $data->day)
            ->with('month', $data->month)
            ->with('plans', $data->plans)
            ->with('fromDate', Carbon::parse($fromDate)->format('Y/m/d H:i'))
            ->with('toDate', Carbon::parse($toDate)->format('Y/m/d H:i'))
            ->with('data', $data);
    }

    private function getData($fromDate, $toDate){
        $data = new \stdClass();

        $subscriptions_data = DB::table('subscriptions')
            ->select(DB::raw('sum(amount) AS earnings'), DB::raw('DATE(created_at) as date'))
            ->where('subscriptions.created_at', '<=', $toDate)
            ->where('subscriptions.created_at', '>=', $fromDate)
            ->where('subscriptions.payment_status', 1)
            ->groupBy('date')
            ->get();


        $rows = insertMissingData($subscriptions_data, ['earnings'], $fromDate, $toDate);

        $data->day = new \stdClass();
        $data->day->earnings = array();
        $data->day->period = array();

        foreach ($rows as $item) {
            $item = (array) $item;
            $data->day->earnings[] = $item['earnings'];
            $data->day->period[] = Carbon::parse($item['date'])->format('F j');
        }

        $rows = DB::table('subscriptions')
            ->select(DB::raw('sum(amount) AS earnings'), DB::raw('count(*) AS orders'), DB::raw('MONTH(created_at) as month'), DB::raw('YEAR(created_at) as year'))
            ->where('subscriptions.created_at', '<=', $toDate)
            ->where('subscriptions.created_at', '>=', $fromDate)
            ->where('subscriptions.payment_status', 1)
            ->groupBy('month')
            ->get();

        $data->month = new \stdClass();

        $data->month->earnings = array();
        $data->month->orders = array();
        $data->month->period = array();

        foreach ($rows as $item) {
            $item = (array) $item;
            $data->month->earnings[] = $item['earnings'];
            $data->month->orders[] = $item['orders'];
            $data->month->period[] = $item['month'] . '/' . $item['year'];
        }

        /** get plan data */
        $data->plans = DB::table('services')
            ->where('services.created_at', '<=', $toDate)
            ->select('id', 'title')
            ->get();


        $data->success = DB::table('subscriptions')->where('payment_status', 1)->where('subscriptions.created_at', '<=', $toDate)->where('subscriptions.created_at', '>=', $fromDate)->count();
        $data->failed = DB::table('subscriptions')->where('payment_status', 0)->where('subscriptions.created_at', '<=', $toDate)->where('subscriptions.created_at', '>=', $fromDate)->count();
        $data->paypal = DB::table('subscriptions')->where('gate', 'paypal')->where('subscriptions.created_at', '<=', $toDate)->where('subscriptions.created_at', '>=', $fromDate)->count();
        $data->stripe = DB::table('subscriptions')->where('gate', 'stripe')->where('subscriptions.created_at', '<=', $toDate)->where('subscriptions.created_at', '>=', $fromDate)->count();

        $data->total = DB::table('subscriptions')
            ->select(DB::raw('sum(amount) AS earnings'))
            ->where('subscriptions.created_at', '<=', $toDate)
            ->where('subscriptions.created_at', '>=', $fromDate)
            ->where('subscriptions.payment_status', 1)
            ->first();

        $data->orders = DB::table('subscriptions')
            ->where('subscriptions.created_at', '<=', $toDate)
            ->where('subscriptions.created_at', '>=', $fromDate)
            ->count();

        return $data;
    }
}