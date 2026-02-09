<?php

namespace App\Http\Controllers;

use App\Album;
use App\Http\Requests\PurchaseRequest;
use App\Purchase;
use App\Services\Purchase\CreatePurchase;
use App\Track;
use Common\Core\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function initiate(PurchaseRequest $request)
    {
        $type = $request->input('purchasable_type');
        $id = $request->input('purchasable_id');

        $model = $type === 'track'
            ? Track::findOrFail($id)
            : Album::findOrFail($id);

        $purchase = app(CreatePurchase::class)->execute([
            'user_id' => Auth::id(),
            'purchasable_type' => $type === 'track' ? Track::class : Album::class,
            'purchasable_id' => $id,
            'gateway_name' => $request->input('gateway'),
            'amount' => $model->price,
            'currency' => $model->currency,
        ]);

        return $this->success([
            'purchase' => $purchase,
        ]);
    }

    public function index(Request $request)
    {
        $purchases = Purchase::forUser(Auth::id())
            ->completed()
            ->with('purchasable')
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('perPage', 20));

        return $this->success(['pagination' => $purchases]);
    }

    public function purchasedItems()
    {
        $purchases = Purchase::forUser(Auth::id())
            ->completed()
            ->get(['purchasable_type', 'purchasable_id']);

        return $this->success(['purchases' => $purchases]);
    }
}
