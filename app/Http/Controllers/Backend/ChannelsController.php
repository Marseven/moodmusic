<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 20:58
 */

namespace App\Http\Controllers\Backend;

use App\Models\Podcast;
use App\Models\PodcastCategory;
use Illuminate\Http\Request;
use DB;
use App\Models\Channel;
use App\Models\Artist;
use App\Models\Album;
use App\Models\Playlist;
use App\Models\User;
use App\Models\Song;
use App\Models\Station;
use App\Models\Genre;
use App\Models\Mood;
use App\Models\Radio;
use Carbon\Carbon;
use Auth;
use Cache;
use Artisan;

class ChannelsController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $channels = DB::table('channels')->select('*')->orderBy('priority', 'asc');

        if($this->request->route()->getName() == 'backend.channels.home')
        {
            $channels = $channels->where('allow_home', 1);
        }

        if($this->request->route()->getName() == 'backend.channels.discover')
        {
            $channels = $channels->where('allow_discover', 1);
        }

        if($this->request->route()->getName() == 'backend.channels.radio')
        {
            $channels = $channels->where('allow_radio', 1);
        }

        if($this->request->route()->getName() == 'backend.channels.community')
        {
            $channels = $channels->where('allow_community', 1);
        }

        if($this->request->route()->getName() == 'backend.channels.trending')
        {
            $channels = $channels->where('allow_trending', 1);
        }

        if($this->request->route()->getName() == 'backend.channels.video')
        {
            $channels = $channels->where('allow_videos', 1);
        }

        if($this->request->route()->getName() == 'backend.channels.genre')
        {
            $channels = $channels->whereRaw("genre REGEXP '(^|,)(" . $this->request->route('id') . ")(,|$)'");
        }

        if($this->request->route()->getName() == 'backend.channels.mood')
        {
            $channels = $channels->whereRaw("mood REGEXP '(^|,)(" . $this->request->route('id') . ")(,|$)'");
        }

        if($this->request->route()->getName() == 'backend.channels.station-category')
        {
            $channels = $channels->whereRaw("radio REGEXP '(^|,)(" . $this->request->route('id') . ")(,|$)'");
        }

        if($this->request->route()->getName() == 'backend.channels.podcast-category')
        {
            $channels = $channels->whereRaw("podcast REGEXP '(^|,)(" . $this->request->route('id') . ")(,|$)'");
        }

        $channels = $channels->get();
        $genres = (new Genre)->get();
        $moods = (new Mood)->get();
        $radio = (new Radio)->get();
        $podcast = (new PodcastCategory())->get();

        return view('backend.channels.index')
            ->with('channels', $channels)
            ->with('genres', $genres)
            ->with('moods', $moods)
            ->with('radio', $radio)
            ->with('podcast', $podcast);

    }

    public function sort()
    {
        $object_ids = $this->request->input('object_ids');

        foreach ($object_ids AS $index => $object_id) {
            DB::table('channels')
                ->where('id', $object_id)
                ->update(['priority' => $index + 1]);
        }

        return redirect()->route('backend.channels.overview')->with('status', 'success')->with('message', 'Priority successfully changed!');
    }

    public function delete()
    {
        DB::table('channels')
            ->where('id', $this->request->route('id'))
            ->delete();

        Cache::clear('homepage');
        Cache::clear('discover');

        return redirect()->route('backend.channels.overview')->with('status', 'success')->with('message', 'Channel successfully deleted!');
    }

    public function add()
    {
        return view('backend.channels.form');
    }

    public function addPost()
    {
        $this->request->validate([
            'title' => 'required|string|max:255',
            'object_type' => 'required',
            'object_ids' => 'nullable|array',
            'meta_title' => 'nullable|string|max:255',
            'visibility' => 'required|boolean',
            'allow_home' => 'required|boolean',
            'allow_discover' => 'required|boolean',
            'allow_radio' => 'required|boolean',
            'allow_community' => 'required|boolean',
            'allow_trending' => 'required|boolean',
            'attraction' => 'required|string',
        ]);

        $title = $this->request->input('title');
        $description = $this->request->input('description');
        $object_type = $this->request->input('object_type');

        if(is_array($this->request->input('object_ids'))) {
            $object_ids = implode(',', $this->request->input('object_ids'));
        } else {
            $object_ids = null;
        }

        $meta_title = $this->request->input('meta_title');
        $meta_description = $this->request->input('meta_description');
        $visibility = $this->request->input('visibility');
        $allow_home = $this->request->input('allow_home');
        $allow_discover = $this->request->input('allow_discover');
        $allow_radio = $this->request->input('allow_radio');
        $allow_community = $this->request->input('allow_community');
        $allow_trending = $this->request->input('allow_trending');
        $allow_podcasts = $this->request->input('allow_podcasts');
        $allow_videos = $this->request->input('allow_videos') ? $this->request->input('allow_videos') : 0;

        $attraction = $this->request->input('attraction');

        $genre = $this->request->input('genre');

        if(is_array($genre))
        {
            $genre = implode(",", $this->request->input('genre'));

        }

        $mood = $this->request->input('mood');

        if(is_array($mood))
        {
            $mood = implode(",", $this->request->input('mood'));

        }

        $radio = $this->request->input('radio');

        if(is_array($radio))
        {
            $radio = implode(",", $this->request->input('radio'));

        }

        $podcast = $this->request->input('podcast');

        if(is_array($podcast))
        {
            $podcast = implode(",", $this->request->input('podcast'));
        }

        DB::table('channels')
            ->insert([
                'user_id' => auth()->user()->id,
                'attraction' => $attraction,
                'title' => $title,
                'description' => $description,
                'alt_name' => str_slug($title) ? str_slug($title) : $title,
                'object_type' => $object_type,
                'object_ids' => $object_ids ? $object_ids : null,
                'meta_description' => $meta_description,
                'meta_title' => $meta_title,
                'visibility' => $visibility,
                'allow_home' => $allow_home,
                'allow_radio' => $allow_radio,
                'allow_discover' => $allow_discover,
                'allow_community' => $allow_community,
                'allow_trending' => $allow_trending,
                'allow_podcasts' => $allow_podcasts,
                'allow_videos' => $allow_videos,
                'genre' => $genre,
                'mood' => $mood,
                'radio' => $radio,
                'podcast' => $podcast,
                'created_at' => Carbon::now()->format('Y/m/d H:i:s'),
                'updated_at' => Carbon::now()->format('Y/m/d H:i:s'),
            ]);

        /**
         * Clear homage cache
         */
        Artisan::call('cache:clear');

        return redirect()->route('backend.channels.overview')->with('status', 'success')->with('message', 'Channel successfully added!');
    }

    public function edit()
    {
        $channel = Channel::findOrFail($this->request->route('id'));

        return view('backend.channels.form')
            ->with('channel', $channel);
    }

    public function editPost()
    {
        $this->request->validate([
            'title' => 'required|string|max:255',
            'object_type' => 'required',
            'object_ids' => 'nullable|array',
            'meta_title' => 'nullable|string|max:255',
            'visibility' => 'required|boolean',
            'allow_home' => 'required|boolean',
            'allow_discover' => 'required|boolean',
            'allow_radio' => 'required|boolean',
            'allow_community' => 'required|boolean',
            'allow_trending' => 'required|boolean',
            'attraction' => 'required|string',
        ]);

        $title = $this->request->input('title');
        $description = $this->request->input('description');
        $object_type = $this->request->input('object_type');

        if(is_array($this->request->input('object_ids'))) {
            $object_ids = implode(',', $this->request->input('object_ids'));
        } else {
            $object_ids = null;
        }

        $meta_title = $this->request->input('meta_title');
        $meta_description = $this->request->input('meta_description');
        $visibility = $this->request->input('visibility');
        $allow_home = $this->request->input('allow_home');
        $allow_discover = $this->request->input('allow_discover');
        $allow_radio = $this->request->input('allow_radio');
        $allow_community = $this->request->input('allow_community');
        $allow_trending = $this->request->input('allow_trending');
        $allow_podcasts = $this->request->input('allow_podcasts');
        $allow_videos = $this->request->input('allow_videos') ? $this->request->input('allow_videos') : 0;
        $attraction = $this->request->input('attraction');

        $genre = $this->request->input('genre');

        if(is_array($genre))
        {
            $genre = implode(",", $this->request->input('genre'));

        }

        $mood = $this->request->input('mood');

        if(is_array($mood))
        {
            $mood = implode(",", $this->request->input('mood'));

        }

        $radio = $this->request->input('radio');

        if(is_array($radio))
        {
            $radio = implode(",", $this->request->input('radio'));

        }

        $podcast = $this->request->input('podcast');

        if(is_array($podcast))
        {
            $podcast = implode(",", $this->request->input('podcast'));

        }

        DB::table('channels')
            ->where('id', $this->request->route('id'))
            ->update([
                'attraction' => $attraction,
                'title' => $title,
                'description' => $description,
                'alt_name' => str_slug($title) ? str_slug($title) : $title,
                'object_type' => $object_type,
                'object_ids' => $object_ids ? $object_ids : null,
                'meta_description' => $meta_description,
                'meta_title' => $meta_title,
                'visibility' => $visibility,
                'allow_home' => $allow_home,
                'allow_radio' => $allow_radio,
                'allow_discover' => $allow_discover,
                'allow_community' => $allow_community,
                'allow_trending' => $allow_trending,
                'allow_podcasts' => $allow_podcasts,
                'allow_videos' => $allow_videos,
                'genre' => $genre,
                'mood' => $mood,
                'radio' => $radio,
                'podcast' => $podcast,
                'updated_at' => Carbon::now()->format('Y/m/d H:i:s'),
            ]);

        /**
         * Clear homage cache
         */
        Artisan::call('cache:clear');

        return redirect()->route('backend.channels.overview')->with('status', 'success')->with('message', 'Channel successfully added!');
    }
}