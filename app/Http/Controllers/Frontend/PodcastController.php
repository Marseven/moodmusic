<?php


namespace App\Http\Controllers\Frontend;

use App\Models\Artist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Podcast;
use App\Models\Episode;
use DB;
use View;
use App\Models\Role;

class PodcastController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

    }

    public function index()
    {

        if( $this->request->is('api*') )
        {
            $podcast = Podcast::findOrFail($this->request->route('id'));
            $podcast->setRelation('episodes', $podcast->episodes()->paginate(20)->each(function ($episode, $key) {
                $episode->makeVisible(['description']);
                $episode->artist = Artist::find($episode->podcast->artist_id);
            }));

            if($this->request->get('callback'))
            {
                foreach ($podcast->episodes as $episode) {
                    $episode->artists = [['name' => $episode->podcast->title]];
                    $episode->artwork_url = $episode->podcast->artwork_url;
                }

                return response()->jsonp($this->request->get('callback'), $podcast->episodes)->header('Content-Type', 'application/javascript');
            }
            return response()->json($podcast);
        }

        $podcast = Podcast::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        if(! $podcast->approved && auth()->check() && Role::getValue('admin_podcasts')) {

        } else {
            if(! isset($podcast->id)) {
                abort(404);
            } elseif(auth()->check() && ! $podcast->visibility && ($podcast->user_id != auth()->user()->id)) {
                abort(404);
            }  elseif(! auth()->check() && ! $podcast->visibility) {
                abort(404);
            } elseif(! $podcast->approved) {
                abort(404);
            }
        }

        $episodes = $podcast->episodes()->with('podcast')->paginate(20);

        if((! $episodes->total() && isset($podcast->rss_feed_url)) || Carbon::now()->gt(Carbon::parse($podcast->updated_at)->addDay())) {
            @libxml_use_internal_errors(true);
            $rss = @simplexml_load_file($podcast->rss_feed_url);

            if (false !== $rss) {

                if (isset($rss->channel)) {
                    if(! $podcast->description) {
                        $podcast->description = strip_tags($rss->channel->description);
                    }

                    $podcast->updated_at = Carbon::now();
                    $podcast->save();

                    if(! $podcast->getFirstMediaUrl('artwork')) {
                        try {
                            $podcast->artwork_url = reset($rss->channel->image->url) ? reset($rss->channel->image->url) : reset($itunes->image->attributes()->href);
                        } catch (\Exception $exception) {
                            // do nothing
                        }
                    }

                }

                if (isset($rss->channel->item)) {
                    foreach ($rss->channel->item as $item) {
                        if (!Episode::where('created_at', Carbon::parse($item->pubDate))->where('podcast_id', $podcast->id)->exists()) {
                            $episode = new Episode();
                            $episode->podcast_id = $podcast->id;
                            $episode->title = $item->title;
                            $episode->description = strip_tags($item->description);
                            $episode->created_at = Carbon::parse($item->pubDate);
                            $episode->type = $item->enclosure['type'];
                            $episode->stream_url = $item->enclosure['url'];
                            $itunes = $item->children('http://www.itunes.com/dtds/podcast-1.0.dtd');
                            $episode->type = 1;
                            $episode->duration = intval(reset($itunes->duration));
                            $episode->explicit = (reset($itunes->explicit) == 'clean' || reset($itunes->explicit) == 'no' ) ? 0 : 1;
                            $episode->save();
                        }
                    }
                    sleep(1);
                }
            }
        }

        $podcast->setRelation('episodes', $podcast->episodes()->with('podcast')->paginate(20));

        $view = View::make('podcast.index')
            ->with('podcast',  $podcast);

        if(isset($podcast->artist)) {
            $artist = $podcast->artist;
            $artist->setRelation('similar', $artist->similar()->paginate(5));
        }

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags($podcast);

        return $view;
    }

    public function subscribers()
    {
        $podcast = Podcast::findOrFail($this->request->route('id'));

        if( $this->request->is('api*') )
        {
            return response()->json($podcast->subscribers);
        }

        $view = View::make('podcast.subscribers')
            ->with('podcast', $podcast);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags($podcast);

        return $view;
    }

    public function episode()
    {
        $episode = Episode::with('podcast')->findOrFail($this->request->route('epid'));

        if( $this->request->is('api*') )
        {
            if($this->request->get('callback'))
            {
                $episode->artists = [['name' => $episode->podcast->title]];
                $episode->artwork_url = $episode->podcast->artwork_url;

                return response()->jsonp($this->request->get('callback'), [$episode])->header('Content-Type', 'application/javascript');
            }

            return response()->json($episode);
        }

        $view = View::make('podcast.episode')
            ->with('episode', $episode);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags($episode);

        return $view;
    }
}