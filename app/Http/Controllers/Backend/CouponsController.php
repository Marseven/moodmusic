<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 21:02
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\Coupon;
use Auth;

class CouponsController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function index()
    {
        $coupons = Coupon::paginate(20);

        return view('backend.coupons.index')
            ->with('coupons', $coupons);
    }

    public function delete()
    {
        Page::where('id', $this->request->route('id'))->delete();
        return redirect()->route('backend.coupons')->with('status', 'success')->with('message', 'Static page successfully deleted!');
    }

    public function add()
    {
        return view('backend.coupons.form');
    }

    public function addPost()
    {
        $this->request->validate([
            'code' => 'required|alpha|unique:coupons',
            'amount' => 'nullable|int',
            'meta_title' => 'nullable|int',
            'usage_limit' => 'nullable|int',
        ]);

        $coupon = new Coupon();
        $coupon->fill($this->request->except('_token'));

        $coupon->approved = $this->request->input('approved') ? 1 : 0;

        $coupon->save();

        return redirect()->route('backend.coupons')->with('status', 'success')->with('message', 'Coupon successfully created!');
    }

    public function edit()
    {
        $coupon = Coupon::findOrFail($this->request->route('id'));
        return view('backend.coupons.form')->with('coupon', $coupon);
    }

    public function editPost()
    {
        $this->request->validate([
            'amount' => 'nullable|int',
            'meta_title' => 'nullable|int',
            'usage_limit' => 'nullable|int',
        ]);

        $coupon = Coupon::findOrFail($this->request->route('id'));

        if($coupon->code != $this->request->input('code')) {
            $this->request->validate([
                'code' => 'required|alpha|unique:coupons',
            ]);
        }

        $coupon->fill($this->request->except('_token'));
        $coupon->approved = $this->request->input('approved') ? 1 : 0;
        $coupon->save();

        return redirect()->route('backend.coupons')->with('status', 'success')->with('message', 'Static page successfully created!');
    }
}