<?php

namespace App\Http\Controllers;

use App\RadioStation;
use Common\Core\BaseController;

class RadioStationController extends BaseController
{
    public function index()
    {
        $stations = RadioStation::active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return $this->success(['stations' => $stations]);
    }

    public function show(RadioStation $radioStation)
    {
        return $this->success(['station' => $radioStation]);
    }
}
