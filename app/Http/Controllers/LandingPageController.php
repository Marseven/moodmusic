<?php

namespace App\Http\Controllers;

use App\Artist;
use Common\Core\BaseController;

class LandingPageController extends BaseController
{
    public function artists()
    {
        $artists = Artist::orderBy('views', 'desc')
            ->take(8)
            ->get();

        $config = [
            'prerender.view' => 'home.show',
            'prerender.config' => 'home.show',
        ];

        return $this->success(['artists' => $artists], 200, $config);
    }
}
