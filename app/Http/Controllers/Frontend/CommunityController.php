<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-26
 * Time: 15:46
 */

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Activity;
use View;
use App\Models\Slide;
use App\Models\Channel;

class CommunityController
{
    private $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function index()
    {
        $community = new \stdClass();
        $community->activities = Activity::where('action', '!=', 'addEvent')->latest()->paginate(20);
        $channels = Channel::where('allow_community', 1)->orderBy('priority', 'asc')->get();
        $slides = Slide::where('allow_community', 1)->orderBy('priority', 'asc')->get();

        if( $this->request->is('api*') )
        {
            return response()->json(array(
                'slides' => json_decode(json_encode($slides)),
                'channels' => json_decode(json_encode($channels)),
                'community' => $community,
            ));
        }

        $view = View::make('community.index')
            ->with('slides', json_decode(json_encode($slides)))
            ->with('channels', json_decode(json_encode($channels)))
            ->with('community', $community);

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