<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use View;
use App\Models\Coupon;
use Cart;

class CartController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

    }

    public function index()
    {
        Cart::session(auth()->user()->id);

        $view = View::make('cart.index')
            ->with('cart',  Cart::getContent());

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags();
        return $view;
    }

    public function overview(){
        Cart::session(auth()->user()->id);
        $items = array();

        foreach(Cart::getContent() as $item) {
            $items[] = $item;
        }

        $cart = new \stdClass();
        $cart->items = $items;
        $cart->subtotal = number_format(Cart::getSubTotal(), 2);
        $cart->currency = config('settings.currency', 'USD');

        return response()->json($cart);
    }

    public function add(){
        $this->request->validate([
            'orderable_id' => 'required|int',
            'orderable_type' => 'required|string|in:App\Models\Album,App\Models\Song,App\Modules\Tickets\models\Ticket',
        ]);

        Cart::session(auth()->user()->id);

        $item = (new $this->request->orderable_type)::findOrFail($this->request->orderable_id);
        $item->key = $this->request->orderable_type . '\\' . $item->id;

        if(! array_key_exists($item->key, Cart::getContent()->toArray())) {
            Cart::add(array(
                'id' => $item->key,
                'name' => $item->title,
                'price' => $item->price,
                'quantity' => 1,
                'attributes' => [
                    'orderable_id' => $this->request->orderable_id,
                    'orderable_type' => $this->request->orderable_type,
                ],
                'associatedModel' => $item
            ));
        }

        return $this->overview();
    }

    public function remove(){
        $this->request->validate([
            'id' => 'required|string',
        ]);
        Cart::session(auth()->user()->id);
        Cart::remove($this->request->id);

        return $this->overview();
    }

    public function applyCoupon (){
        $this->request->validate([
            'code' => 'required|string',
        ]);

        Cart::session(auth()->user()->id);
        $coupon = Coupon::where('code', $this->request->input('code'))->first();

        if(isset($coupon->id)) {
            if($coupon->expired_at && $coupon->expired_at < Carbon::now()) {
                return response()->json([
                    'message' => 'failed',
                    'errors' => array('message' => array(__('web.COUPON_EXPIRED')))
                ], 403);
            }

            if($coupon->usage_limit && $coupon->use_count > $coupon->usage_limit) {
                return response()->json([
                    'message' => 'failed',
                    'errors' => array('message' => array(__('web.COUPON_LIMITED')))
                ], 403);
            }

            if($coupon->minimum_spend && $coupon->minimum_spend > Cart::getTotal()) {
                return response()->json([
                    'message' => 'failed',
                    'errors' => array('message' => array(__('web.COUPON_MINIMUM_ERROR', ['amount' => $coupon->minimum_spend . config('settings.currency', 'USD')])))
                ], 403);
            }

            if($coupon->maximum_spend && $coupon->maximum_spend < Cart::getTotal()) {
                return response()->json([
                    'message' => 'failed',
                    'errors' => array('message' => array(__('web.COUPON_MAXIMUM_ERROR', ['amount' => $coupon->maximum_spend . config('settings.currency', 'USD')])))
                ], 403);
            }

            Cart::condition([
                new \Darryldecode\Cart\CartCondition(array(
                    'name' => $coupon->code,
                    'type' => 'coupon',
                    'target' => 'total',
                    'value' => $coupon->type == 'percentage' ? '-' . $coupon->amount . '%' : '-' . $coupon->amount,
                    'order' => 1
                ))
            ]);

        } else {
            return response()->json([
                'message' => 'failed',
                'errors' => array('message' => array(__('web.COUPON_NOT_EXIST')))
            ], 403);

        }
    }
}