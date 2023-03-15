<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-24
 * Time: 13:08
 */

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use App\Models\Genre;
use App\Models\Mood;
use App\Models\Slide;
use App\Models\Channel;
use View;
use Cache;

class DiscoverController
{
    public function index(Request $request)
    {

        $discover = (Object) array();

        if(! Cache::has('discover')) {
            $discover->channels = Channel::where('allow_discover', 1)->orderBy('priority', 'asc')->get();
            $discover->slides = Slide::where('allow_discover', 1)->orderBy('priority', 'asc')->get();
            $discover->genres = Genre::orderBy('priority', 'asc')->where('discover', 1)->get();
            $discover->moods = Mood::orderBy('priority', 'asc')->get();

            Cache::put('discover', json_encode($discover), Carbon::now()->addHour());
            $discover = json_decode(json_encode($discover));
        } else {
            $discover = json_decode(Cache::get('discover'));
        }

        if( $request->is('api*') )
        {
            return response()->json($discover);
        }

        $view = View::make('discover.index')->with('discover', $discover);

        if($request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags();

        return $view;
    }
}