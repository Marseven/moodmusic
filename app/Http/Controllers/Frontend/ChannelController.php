<?php
/**
 * Created by PhpStorm.
 * User: lechchut
 * Date: 6/3/19
 * Time: 10:52 AM
 */

namespace App\Http\Controllers\Frontend;

use App\Models\Podcast;
use Illuminate\Http\Request;
use View;
use DB;
use App\Models\Artist;
use App\Models\Song;
use App\Models\Album;
use App\Models\Playlist;
use App\Models\Station;
use App\Models\User;
use App\Models\Channel;
use MetaTag;

class ChannelController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $channel = Channel::where('alt_name', $this->request->route('slug'))->firstOrFail();

        $view = View::make('channel.index')
            ->with('channel', $channel);

        if($this->request->is('api*') || $this->request->wantsJson())
        {
            return response()->json($channel);
        }

        if($this->request->ajax()) {
            $sections = $view->renderSections();

            if($this->request->input('page') && intval($this->request->input('page')) > 1)
            {
                return $sections['pagination'];
            } else {
                return $sections['content'];
            }
        }

        MetaTag::set('title', $channel->meta_title);
        MetaTag::set('description', $channel->meta_description);

        return $view;
    }
}