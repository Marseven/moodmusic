<?php

namespace App\Http\Controllers;

use App\Vibe;
use Common\Core\BaseController;

class VibeController extends BaseController
{
    public function index()
    {
        $vibes = Vibe::where('is_active', true)
            ->with('genre:id,name,display_name')
            ->orderBy('position')
            ->get();

        return $this->success(['vibes' => $vibes]);
    }
}
