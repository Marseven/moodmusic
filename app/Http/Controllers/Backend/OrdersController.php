<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-06
 * Time: 23:14
 */

namespace App\Http\Controllers\Backend;

use App\Models\Order;
use Illuminate\Http\Request;
use DB;

class OrdersController
{
    private $request;
    private $select;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $orders = Order::withoutGlobalScopes()->orderBy('id', 'desc');

        isset($_GET['q']) ? $term = $_GET['q'] : $term = '';

        if($term) {
            $orders = $orders->whereRaw('users.name LIKE "%' . $term . '%"');
        }

        $orders = $orders->paginate(20);

        $stats = Order::select(DB::raw('sum(amount) AS revenue'), DB::raw('sum(commission) AS commission'))->first();
        $stats->album = Order::select(DB::raw('count(*) AS count'), DB::raw('sum(amount) AS revenue'))->where('orderable_type', 'App\Models\Album')->first();
        $stats->song = Order::select(DB::raw('count(*) AS count'), DB::raw('sum(amount) AS revenue'))->where('orderable_type', 'App\Models\Song')->first();

        return view('backend.orders.index')
            ->with('term', $term)
            ->with('orders', $orders)
            ->with('stats', $stats);
    }
}