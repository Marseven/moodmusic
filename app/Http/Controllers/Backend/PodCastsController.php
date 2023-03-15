<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-26
 * Time: 10:54
 */

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use App\Models\Podcast;
use Image;
use App\Models\Episode;

class PodCastsController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index() {

        $podcasts = Podcast::withoutGlobalScopes();

        if ($this->request->has('term'))
        {
            if($this->request->has('location')) {
                switch ($this->request->input('location')) {
                    case 0:
                        $podcasts = $podcasts->search($this->request->input('term'));
                        break;
                    case 1:
                        $podcasts = $podcasts->where('title', 'like', '%' . $this->request->input('term') . '%');
                        break;
                    case 2:
                        $podcasts = $podcasts->where('description', 'like', '%' . $this->request->input('term') . '%');
                        break;
                }
            } else {
                $podcasts = $podcasts->where('title', 'like', '%' . $this->request->input('term') . '%');
            }

        }

        if ($this->request->input('userIds') && is_array($this->request->input('userIds')))
        {
            $podcasts = $podcasts->where(function ($query) {
                foreach($this->request->input('userIds') as $index => $userId) {
                    if($index == 0) {
                        $query->where('user_id', '=', $userId);
                    } else {
                        $query->orWhere('user_id', '=', $userId);
                    }
                }
            });
        }

        if ($this->request->input('category') && is_array($this->request->input('category')))
        {
            $podcasts = $podcasts->where('category', 'REGEXP', '(^|,)(' . implode(',', $this->request->input('category')) . ')(,|$)');
        }

        if ($this->request->input('created_from'))
        {
            $podcasts = $podcasts->where('created_at', '>=', Carbon::parse($this->request->input('created_from')));
        }

        if ($this->request->has('created_until'))
        {
            $podcasts = $podcasts->where('created_at', '<=', Carbon::parse($this->request->input('created_until')));
        }

        if ($this->request->input('comment_count_from'))
        {
            $podcasts = $podcasts->where('comment_count', '>=', intval($this->request->input('comment_count_from')));
        }

        if ($this->request->has('comment_count_until'))
        {
            $podcasts = $podcasts->where('comment_count', '<=', intval($this->request->input('comment_count_until')));
        }

        if ($this->request->has('fixed'))
        {
            $podcasts = $podcasts->where('fixed', '=', 1);
        }

        if ($this->request->has('comment_disabled'))
        {
            $podcasts = $podcasts->where('allow_comments', '=', 0);
        }

        if ($this->request->has('hidden'))
        {
            $podcasts = $podcasts->where('visibility', '=', 0);
        }

        if ($this->request->has('country'))
        {
            $podcasts = $podcasts->where('country_code', '=', $this->request->input('country'));
        }

        if ($this->request->has('city'))
        {
            $podcasts = $podcasts->where('city_id', '=', $this->request->input('city'));
        }

        if ($this->request->has('language'))
        {
            $podcasts = $podcasts->where('language_id', '=', $this->request->input('language'));
        }

        if ($this->request->has('title'))
        {
            $podcasts = $podcasts->orderBy('title', $this->request->input('title'));
        }

        if ($this->request->has('created_at'))
        {
            $podcasts = $podcasts->orderBy('created_at', $this->request->input('created_at'));
        }

        if ($this->request->has('results_per_page'))
        {
            $podcasts = $podcasts->paginate(intval($this->request->input('results_per_page')));
        } else {
            $podcasts = $podcasts->paginate(20);
        }

        return view('backend.podcasts.index')
            ->with('podcasts', $podcasts);
    }

    public function add()
    {
        return view('backend.podcasts.form');
    }

    public function edit()
    {
        $podcast = Podcast::findOrFail($this->request->route('id'));
        return view('backend.podcasts.form')->with('podcast', $podcast);
    }

    public function savePost()
    {
        $this->request->validate([
            'title' => 'required|string',
        ]);

        if(request()->route()->getName() == 'backend.podcasts.add.post') {
            $podcast = new Podcast();
        } else {
            $podcast = Podcast::findOrFail($this->request->route('id'));
        }

        $podcast->title = $this->request->input('title');
        $podcast->description = $this->request->input('description');
        $podcast->rss_feed_url = $this->request->input('rss_feed_url');
        $podcast->country_code = $this->request->input('country_code');
        $podcast->language_id = $this->request->input('language_id');
        $podcast->artist_id = $this->request->input('artist_id');
        $podcast->user_id = auth()->user()->id;
        $category = $this->request->input('category');

        if(is_array($category))
        {
            $podcast->category = implode(",", $this->request->input('category'));
        } else {
            $podcast->category = null;
        }

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);

            if(request()->route()->getName() == 'backend.podcasts.edit.post') {
                $podcast->clearMediaCollection('artwork');
            }

            $podcast->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        $podcast->save();

        return redirect()->route('backend.podcasts')->with('status', 'success')->with('message', 'Podcast successfully edited!');
    }

    public function import()
    {

        $this->request->validate([
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'rss_feed_url' => 'required|string',
        ]);

        $podcast = new Podcast();

        $podcast->rss_feed_url = $this->request->input('rss_feed_url');
        $podcast->country_code = $this->request->input('country_code');
        $podcast->language_id = $this->request->input('language_id');
        $podcast->artist_id = $this->request->input('artist_id');
        $podcast->user_id = auth()->user()->id;
        $category = $this->request->input('category');

        if(is_array($category))
        {
            $podcast->category = implode(",", $this->request->input('category'));
        } else {
            $podcast->category = null;
        }

        @libxml_use_internal_errors(true);
        $rss = @simplexml_load_file($this->request->input('rss_feed_url'));

        if (false === $rss) {
            return redirect()->back()->with('status', 'failed')->with('message', 'Can not fetch the rss.');
        }

        if (isset($rss->channel)) {

            if($this->request->input('title')) {
                $podcast->title = $this->request->input('title');
            } else {
                $podcast->title = $rss->channel->title;
            }

            if($this->request->input('description')) {
                $podcast->description = $this->request->input('description');
            } else {
                $podcast->description = $rss->channel->description;
            }

            if($rss->channel->children('http://www.itunes.com/dtds/podcast-1.0.dtd')) {
                $itunes = $rss->channel->children('http://www.itunes.com/dtds/podcast-1.0.dtd');
                $podcast->addMediaFromUrl(reset($rss->channel->image->url) ? reset($rss->channel->image->url) : reset($itunes->image->attributes()->href))
                    ->usingFileName(time(). '.jpg')
                    ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
            }
            $podcast->created_at = Carbon::parse($rss->channel->pubDate);

            if(isset($rss->channel->lastBuildDate)) {
                $podcast->updated_at = Carbon::parse($rss->channel->lastBuildDate);

            }

            $podcast->save();
        } else {
            return redirect()->back()->with('status', 'failed')->with('message', 'RSS format does not match a podcast feed.');
        }

        if (isset($rss->channel->item)) {
            foreach ($rss->channel->item as $item) {
                if (!Episode::where('created_at', Carbon::parse($item->pubDate))->where('podcast_id', $podcast->id)->exists()) {
                    $episode = new Episode();
                    $episode->podcast_id = $podcast->id;
                    $episode->title = $item->title;
                    $episode->description = $item->description;
                    $episode->created_at = Carbon::parse($item->pubDate);
                    $episode->type = $item->enclosure['type'];
                    $episode->stream_url = $item->enclosure['url'];
                    if($item->children('http://www.itunes.com/dtds/podcast-1.0.dtd')) {
                        $itunes = $item->children('http://www.itunes.com/dtds/podcast-1.0.dtd');
                        $episode->type = 1;
                        $episode->duration = intval(reset($itunes->duration));
                        $episode->explicit = (reset($itunes->explicit) == 'clean' || reset($itunes->explicit) == 'no' ) ? 0 : 1;
                    } else {
                        $episode->duration = intval($item->enclosure['length']);
                    }
                    $episode->save();
                }
            }
        }

        return redirect()->route('backend.podcasts')->with('status', 'success')->with('message', 'Podcast successfully edited!');
    }

    public function delete()
    {
        $podcast = Podcast::findOrFail($this->request->route('id'));
        $podcast->delete();
        return redirect()->route('backend.podcasts')->with('status', 'success')->with('message', 'Podcast successfully deleted!');
    }

    public function episodes()
    {
        $podcast = Podcast::withoutGlobalScopes()->findOrFail($this->request->route('id'));
        $podcast->setRelation('episodes', $podcast->episodes()->withoutGlobalScopes()->paginate(20));

        return view('backend.podcasts.episodes')
            ->with('podcast', $podcast);
    }

    public function uploadEpisode()
    {
        $podcast = Podcast::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        return view('backend.podcasts.upload')
            ->with('podcast', $podcast);
    }

    public function episodeDelete()
    {
        $episode = Episode::withoutGlobalScopes()->findOrFail($this->request->route('eid'));
        $episode->delete();

        return redirect()->back()->with('status', 'success')->with('message', 'Episode successfully deleted!');
    }

    public function episodeEdit()
    {
        $podcast = Podcast::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        $episode = Episode::withoutGlobalScopes()->findOrFail($this->request->route('eid'));

        return view('backend.podcasts.episodeForm')
            ->with('podcast', $podcast)
            ->with('episode', $episode);
    }

    public function episodesMassAction()
    {
        $this->request->validate([
            'action' => 'required|string',
            'ids' => 'required|array',
        ]);

       if($this->request->input('action') == 'remove_from_podcast') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $episode = Episode::withoutGlobalScopes()->where('id', $id)->first();
                $episode->delete();
            }
            return redirect()->back()->with('status', 'success')->with('message', 'Episodes successfully deleted!');
        }
    }

    public function cityByCountryCode()
    {
        $this->request->validate([
            'countryCode' => 'required|string|max:3'
        ]);

        return makeCityDropDown($this->request->input('countryCode'), 'city_id', 'form-control select2-active', $selected = null);
    }

    public function languageByCountryCode()
    {
        $this->request->validate([
            'countryCode' => 'required|string|max:3'
        ]);

        return makeCountryLanguageDropDown($this->request->input('countryCode'), 'language_id', 'form-control select2-active', $selected = null);
    }
}
