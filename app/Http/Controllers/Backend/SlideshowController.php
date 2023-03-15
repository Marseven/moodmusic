<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 21:01
 */

namespace App\Http\Controllers\Backend;

use App\Models\Podcast;
use App\Models\PodcastCategory;
use Illuminate\Http\Request;
use DB;
use App\Models\Playlist;
use App\Models\Song;
use App\Models\Artist;
use App\Models\Album;
use App\Models\Slide;
use App\Models\Station;
use App\Models\Genre;
use App\Models\Mood;
use App\Models\Radio;
use App\Models\User;
use Carbon\Carbon;
use Auth;
use Cache;
use Image;

class SlideshowController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $slides = Slide::withoutGlobalScopes()->orderBy('priority', 'asc');

        if($this->request->route()->getName() == 'backend.slideshow.home')
        {
            $slides = $slides->where('allow_home', 1);
        }

        if($this->request->route()->getName() == 'backend.slideshow.discover')
        {
            $slides = $slides->where('allow_discover', 1);
        }

        if($this->request->route()->getName() == 'backend.slideshow.radio')
        {
            $slides = $slides->where('allow_radio', 1);
        }

        if($this->request->route()->getName() == 'backend.slideshow.community')
        {
            $slides = $slides->where('allow_community', 1);
        }

        if($this->request->route()->getName() == 'backend.slideshow.trending')
        {
            $slides = $slides->where('allow_trending', 1);
        }

        if($this->request->route()->getName() == 'backend.slideshow.genre')
        {
            $slides = $slides->whereRaw("genre REGEXP '(^|,)(" . $this->request->route('id') . ")(,|$)'");
        }

        if($this->request->route()->getName() == 'backend.slideshow.mood')
        {
            $slides = $slides->whereRaw("mood REGEXP '(^|,)(" . $this->request->route('id') . ")(,|$)'");
        }

        if($this->request->route()->getName() == 'backend.slideshow.station-category')
        {
            $slides = $slides->whereRaw("radio REGEXP '(^|,)(" . $this->request->route('id') . ")(,|$)'");
        }

        if($this->request->route()->getName() == 'backend.slideshow.podcast-category')
        {
            $slides = $slides->whereRaw("podcast REGEXP '(^|,)(" . $this->request->route('id') . ")(,|$)'");
        }

        $slides = $slides->get();
        $genres = Genre::where('discover', 1)->get();
        $moods = Mood::all();
        $radio = Radio::all();
        $podcast = PodcastCategory::all();

        Cache::clear('homepage');
        Cache::clear('discover');

        return view('backend.slideshow.index')
            ->with('slides', $slides)
            ->with('genres', $genres)
            ->with('moods', $moods)
            ->with('radio', $radio)
            ->with('podcast', $podcast);
    }

    public function sort()
    {
        $slideshowIds = $this->request->input('slideshowIds');

        foreach ($slideshowIds AS $index => $slideshowId) {
            DB::table('slides')
                ->where('id', $slideshowId)
                ->update(['priority' => $index + 1]);
        }

        return redirect()->route('backend.slideshow.overview')->with('status', 'success')->with('message', 'Priority successfully changed!');
    }

    public function add()
    {
        return view('backend.slideshow.form');
    }

    public function delete(Request $request)
    {
        $slide = Slide::findOrFail($request->route('id'));
        $slide->delete();
        return redirect()->route('backend.slideshow.overview')->with('status', 'success')->with('message', 'Slide show successfully deleted!');
    }

    public function addPost()
    {
        $this->request->validate([
            'object_id' => 'required',
            'object_type' => 'required',
            'title' => 'required|string|max:100',
            'title_link' => 'nullable|string|max:150',
            'visibility' => 'required|boolean',
            'allow_home' => 'required|boolean',
            'allow_discover' => 'required|boolean',
            'allow_radio' => 'required|boolean',
            'allow_community' => 'required|boolean',
            'allow_trending' => 'required|boolean',
            'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
        ]);

        $slide = new Slide();
        $slide->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)), intval(500 * 0.5625))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        $slide->object_id = $this->request->input('object_id');
        $slide->object_type = $this->request->input('object_type');
        $slide->description = $this->request->input('description');
        $slide->visibility = $this->request->input('visibility');
        $slide->allow_home = $this->request->input('allow_home');
        $slide->allow_discover = $this->request->input('allow_discover');
        $slide->allow_radio = $this->request->input('allow_radio');
        $slide->allow_community = $this->request->input('allow_community');
        $slide->allow_podcasts = $this->request->input('allow_podcasts');
        $slide->allow_trending = $this->request->input('allow_trending');
        $slide->allow_videos = $this->request->input('allow_videos') ? $this->request->input('allow_videos') : 0;
        $slide->title = $this->request->input('title');
        $slide->title_link = clearUrlForMetatags($this->request->input('title_link'));
        $slide->user_id = auth()->user()->id;

        $genre = $this->request->input('genre');

        if(is_array($genre))
        {
            $slide->genre = implode(",", $this->request->input('genre'));

        }

        $mood = $this->request->input('mood');

        if(is_array($mood))
        {
            $slide->mood = implode(",", $this->request->input('mood'));

        }

        $radio = $this->request->input('radio');

        if(is_array($radio))
        {
            $slide->radio = implode(",", $this->request->input('radio'));

        }

        $podcast = $this->request->input('podcast');

        if(is_array($podcast))
        {
            $slide->podcast = implode(",", $this->request->input('podcast'));

        }

        $slide->save();

        /**
         * Clear homage cache
         */
        Cache::clear('homepage');
        Cache::clear('discover');

        return redirect()->route('backend.slideshow.overview')->with('status', 'success')->with('message', 'Slide successfully added!');

    }

    public function edit(Request $request)
    {
        $slide = Slide::findOrFail($request->route('id'));

        $song = (object) array();
        $artist = (object) array();
        $playlist = (object) array();
        $album = (object) array();
        $podcast = (object) array();
        $user = (object) array();
        $video = (object) array();

        if ($slide->object_type == "song") {
            $song = Song::find($slide->object_id);
        } elseif ($slide->object_type == "playlist") {
            $playlist = Playlist::find($slide->object_id);
        } elseif ($slide->object_type == "artist") {
            $artist = Artist::find($slide->object_id);
        } elseif ($slide->object_type == "playlist") {
            $album = Album::find($slide->object_id);
        } elseif ($slide->object_type == "station") {
            $podcast = Station::find($slide->object_id);
        } elseif ($slide->object_type == "user") {
            $user = User::find($slide->object_id);
        } elseif ($slide->object_type == "podcast") {
            $podcast = Podcast::find($slide->object_id);
        } elseif ($slide->object_type == "video") {
            $video = \App\Modules\Video\Video::find($slide->object_id);
        }

        return view('backend.slideshow.form')
            ->with('slide', $slide)
            ->with('artist', $artist)
            ->with('song', $song)
            ->with('playlist', $playlist)
            ->with('album', $album)
            ->with('station', $podcast)
            ->with('user', $user)
            ->with('podcast', $podcast)
            ->with('video', $video);
    }

    public function editPost()
    {
        $this->request->validate([
            'object_id' => 'required',
            'object_type' => 'required',
            'title' => 'required|string|max:100',
            'title_link' => 'nullable|string|max:150',
            'visibility' => 'required|boolean',
            'allow_home' => 'required|boolean',
            'allow_discover' => 'required|boolean',
            'allow_radio' => 'required|boolean',
            'allow_community' => 'required|boolean',
            'allow_trending' => 'required|boolean',
        ]);

        $slide = Slide::findOrFail($this->request->route('id'));

        $slide->object_id = $this->request->input('object_id');
        $slide->object_type = $this->request->input('object_type');
        $slide->description = $this->request->input('description');
        $slide->visibility = $this->request->input('visibility');
        $slide->allow_home = $this->request->input('allow_home');
        $slide->allow_discover = $this->request->input('allow_discover');
        $slide->allow_radio = $this->request->input('allow_radio');
        $slide->allow_community = $this->request->input('allow_community');
        $slide->allow_podcasts = $this->request->input('allow_podcasts');
        $slide->allow_trending = $this->request->input('allow_trending');
        $slide->allow_videos = $this->request->input('allow_videos') ? $this->request->input('allow_videos') : 0;

        $slide->title = $this->request->input('title');
        $slide->title_link = clearUrlForMetatags($this->request->input('title_link'));

        $genre = $this->request->input('genre');

        if(is_array($genre))
        {
            $slide->genre = implode(",", $this->request->input('genre'));

        }

        $mood = $this->request->input('mood');

        if(is_array($mood))
        {
            $slide->mood = implode(",", $this->request->input('mood'));

        }

        $radio = $this->request->input('radio');

        if(is_array($radio))
        {
            $slide->radio = implode(",", $this->request->input('radio'));

        }

        $podcast = $this->request->input('podcast');

        if(is_array($podcast))
        {
            $slide->podcast = implode(",", $this->request->input('podcast'));

        }

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);

            $slide->clearMediaCollection('artwork');
            $slide->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)), intval(500 * 0.5625))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        $slide->save();

        /**
         * Clear homage cache
         */
        Cache::clear('homepage');
        Cache::clear('discover');

        return redirect()->route('backend.slideshow.overview')->with('status', 'success')->with('message', 'Slide successfully edited!');
    }
}