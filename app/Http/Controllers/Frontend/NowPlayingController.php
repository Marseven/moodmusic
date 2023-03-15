<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-28
 * Time: 15:44
 */

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use View;
use App\Models\Song;

class NowPlayingController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {

        $suggest = Song::where('loves', '>', '0')->limit(20)->get();

        $view = View::make('profile.now_playing')->with('suggest', $suggest);

        if ($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags();

        return $view;
    }
}