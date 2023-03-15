<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use App\Models\Channel;
use App\Models\Slide;
use View;
use MetaTag;
use Auth;
use Cache;

/**
 *
 * Class Homepage
 * @package App\Http\Controllers\Frontend
 */
class HomeController
{
    private $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }



    public function index() {
        $home = new \stdClass();

        if(! Cache::has('homepage')) {
            $home->channels = Channel::where('allow_home', 1)->orderBy('priority', 'asc')->get();
            $home->slides = Slide::where('allow_home', 1)->get();
            Cache::put('homepage', json_encode($home), Carbon::now()->addHour());
            $home = json_decode(json_encode($home));
        } else {
            $home = json_decode(Cache::get('homepage'));
        }

        /**
         * if use is signed in then get recent plays
         */

        if(auth()->check())
        {
            $home->recentListens = auth()->user()->recent()->latest()->paginate(20);
            $home->obsessedSongs = auth()->user()->obsessed()->paginate(20);
            $home->userCommunitySongs = auth()->user()->communitySongs()->latest()->paginate(20);
            if(Cache::has('trending_day')) {
                $home->popularSongs  = Cache::get('trending_day');
            }
        }

        /**
         * If is API request, return json only
         */
        if( $this->request->is('api*') )
        {
            return response()->json($home);
        }

        $view = View::make('homepage.index')->with('home', $home);

        /**
         * If not API request, render the view
         */
        if($this->request->ajax()) {

            $sections = $view->renderSections();
            return $sections['content'];
        }

        /** default metatags can change by settings */
        getMetatags();


        return $view;
    }
}
