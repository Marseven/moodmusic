<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-18
 * Time: 24:20
 */

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use App\Models\Song;
use App\Models\Slide;
use App\Models\Channel;
use Route;
use View;
use Cache;

class TrendingController
{
    private $request;

    private $range;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->range = Route::currentRouteName();
        switch ($this->range == 'frontend.trending') {
            case 'frontend.trending.week':
                $this->range = 7;
                break;
            case 'frontend.trending.month':
                $this->range = 30;
                break;
            default:
                $this->range = 0;
        }
    }

    public function index()
    {
        $trending = (Object) array();

        switch (Route::currentRouteName()) {
            case 'frontend.trending.week':
                $start_date = Carbon::now()->subWeeks(2);
                $end_date = Carbon::now()->subWeek();
                $cache_key = 'trending_week';
                break;
            case 'frontend.trending.month':
                $start_date = Carbon::now()->subMonths(2);
                $end_date = Carbon::now()->subMonth();
                $cache_key = 'trending_month';
                break;
            default:
                $start_date = Carbon::now()->subDays(2);
                $end_date = Carbon::now()->subDay();
                $cache_key = 'trending_day';
        }

        if(Cache::has($cache_key)) {
            $trending->songs = Cache::get($cache_key);
        } else {
            $lastTimeToCompare = $this->songs($start_date, $end_date)->toArray();
            $lastTimeToCompare = $lastTimeToCompare['data'];

            $present = $this->songs($start_date, Carbon::now());

            $trending->songs = $present->map(function ($row) use ($lastTimeToCompare) {
                if(isset($row->id)) {
                    $song = new \stdClass();
                    $song = $row;
                    $song->last_postion = array_search($row->id, array_column($lastTimeToCompare, 'id'));
                    return $song;
                }
            });

            Cache::put($cache_key, $trending->songs, now()->addDay());
        }

        $trending->channels = Channel::where('allow_trending', 1)->orderBy('priority', 'asc')->get();
        $trending->slides = Slide::where('allow_trending', 1)->orderBy('priority', 'asc')->get();

        $trending = json_decode(json_encode($trending));

        if( $this->request->is('api*') ) {
            return response()->json($trending);
        }

        if($this->request->ajax()) {
            $view = View::make('trending.index')->with('trending', $trending);
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags();

        return view('trending.index')->with('trending', $trending);
    }

    public function songs($start_date, $end_date, $limit = 50)
    {
        return Song::leftJoin('popular', 'popular.song_id', '=', 'songs.id')
            ->select('songs.*', DB::raw('sum(popular.plays) AS total_plays'))
            ->where('popular.created_at', '<=',  $end_date)
            ->where('popular.created_at', '>=',  $start_date)
            ->groupBy('popular.song_id')
            ->orderBy('total_plays', 'desc')
            ->paginate($limit);
    }
}
