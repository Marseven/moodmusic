<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-26
 * Time: 15:46
 */

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Activity;
use Route;
use View;
use DB;

class HashTagController
{
    private $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function index($slug)
    {
        if(Route::currentRouteName() == 'frontend.hashtag') {
            $tags = DB::table('hash_tags')
                ->leftJoin('activities', 'hash_tags.hashable_id', '=', 'activities.id')
                ->select('hashable_id')
                ->where('tag', '=', $slug )
                ->orderBy('activities.comment_count', 'desc')
                ->paginate(20);


        } else {
            $tags = DB::table('hash_tags')
                ->select('hashable_id')
                ->orderBy('id', 'desc')
                ->where('tag', '=', $slug )
                ->paginate(20);
        }

        $total = DB::table('hash_tags')
            ->groupBy('tag')
            ->where('tag', '=', $slug )
            ->count();

        $ids = [];

        foreach ($tags as $tag) {
            $ids[] = $tag->hashable_id;
        }

        if( ! count($ids)) {
            abort(404);
        }

        $activities = Activity::whereIn('id', $ids)->orderBy(DB::raw('FIELD(id, ' .  implode(',', $ids) . ')', 'FIELD'))->get();
        $view = View::make('hashtag.index')
            ->with('tag', $slug)
            ->with('activities', $activities)
            ->with('total', $total);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            if($this->request->input('page') && intval($this->request->input('page')) > 1)
            {
                return $sections['pagination'];
            } else {
                return $sections['content'];
            }
        }
        return $view;
    }
}