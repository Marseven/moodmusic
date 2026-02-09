<?php

namespace App\Http\Controllers\Admin;

use App\Purchase;
use Common\Core\BaseController;
use Common\Database\Datasource\Datasource;
use Illuminate\Http\Request;

class PurchaseAdminController extends BaseController
{
    public function __construct(protected Request $request)
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $dataSource = new Datasource(
            Purchase::with(['user', 'purchasable']),
            $this->request->all(),
        );

        $pagination = $dataSource->paginate();

        return $this->success(['pagination' => $pagination]);
    }
}
