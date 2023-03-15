<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-30
 * Time: 11:09
 */

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use View;
use App\Models\Mood;
use App\Models\Song;
use App\Models\Slide;
use App\Models\Channel;
use MetaTag;

class MoodController
{
    private $request;
    private $mood;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    private function getMood(){
        $this->mood = Mood::where('alt_name',  $this->request->route('slug'))->firstOrFail();
        /** set metatags for mood section */
        MetaTag::set('title', $this->mood->meta_title ? $this->mood->meta_title : $this->mood->name);
        MetaTag::set('description', $this->mood->meta_description ? $this->mood->meta_description : $this->mood->description);
        MetaTag::set('keywords', $this->mood->meta_keywords);
        MetaTag::set('image', $this->mood->artwork);
    }

    public function index()
    {
        $this->getMood();
        $mood = $this->mood;
        $channels = Channel::where('mood', 'REGEXP', '(^|,)(' . $this->mood->id . ')(,|$)')->orderBy('priority', 'asc')->get();
        $slides = Slide::where('mood', 'REGEXP', '(^|,)(' . $this->mood->id . ')(,|$)')->orderBy('priority', 'asc')->get();
        $mood->songs = Song::where('mood', 'REGEXP', '(^|,)(' . $this->mood->id . ')(,|$)')->paginate(20);

        if( $this->request->is('api*') )
        {
            return response()->json(array(
                'slides' => json_decode(json_encode($slides)),
                'channels' => json_decode(json_encode($channels)),
                'mood' => $mood,
            ));
        }

        $mood->related = Mood::where('id', '!=',  $this->mood->id);

        $view = View::make('mood.index')
            ->with('slides', json_decode(json_encode($slides)))
            ->with('channels', json_decode(json_encode($channels)))
            ->with('mood', $mood);

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
