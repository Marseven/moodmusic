<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-02
 * Time: 16:38
 */

namespace App\Http\Controllers\Frontend;

use App\Jobs\GetAlbumDetails;
use App\Models\Playlist;
use App\Models\Podcast;
use App\Models\Station;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use View;
use App\Models\Artist;
use App\Models\Song;
use App\Models\Album;
use App\Models\User;
use App\Models\City;
use App\Models\Event;
use DB;
use App\Modules\Spotify\Spotify;

class SearchController
{
    private $request;
    private $term;
    private $limit;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->term = $this->request->is('api*') ? $this->request->input('q') : $this->request->route('slug');
        (isset($_GET['limit']) ? $this->limit = intval($_GET['limit']) :  $this->limit = 20);
    }

    public function song()
    {
        if(config('settings.automate') && ! $this->request->is('api*') && ! $this->request->wantsJson()) {
            if (intval($this->request->input('page')) < 2) {
                $songs = array();
                $data = (new Spotify)->searchTracks($this->term)->get();
                foreach ($data['tracks']['items'] as $item) {
                    $artists = handleSpotifyArtists($item['album']['artists']);
                    if ($item['album']['album_type'] != 'single') {
                        //handleSpotifyAlbum($artists, $item['album']);
                    }

                    $songs[] = handleSpotifySong($item, $artists);

                }
            }
        }

        $result = (Object) array();

        if(isset($songs) && count($songs)) {
            $result->songs = $songs;
        } else {
            $result->songs = Song::where('title', 'like', '%' . $this->term . '%')->paginate(20);
        }

        if( $this->request->is('api*') )
        {
            return response()->json($result->songs);
        }

        $view = View::make('search.song')
            ->with('result', $result)
            ->with('term', $this->term);

        if($this->request->ajax()) {
            $sections = $view->renderSections();

            if($this->request->input('page') && intval($this->request->input('page')) > 1)
            {
                return $sections['pagination'];
            } else {
                return $sections['content'];
            }
        }

        $item = new \stdClass();
        $item->term = $this->term;
        getMetatags($item);

        return $view;
    }

    public function artist()
    {
        if(config('settings.automate') && ! $this->request->is('api*') && ! $this->request->wantsJson()) {
            $artists = array();
            if (intval($this->request->input('page')) < 2) {
                $data = (new Spotify)->searchArtists($this->term)->get();
                $artists = handleSpotifyArtists($data['artists']['items']);
            }
        }

        $result = (Object) array();

        if(isset($artists) && count($artists)) {
            $result->artists = $artists;
        } else {
            $result->artists = Artist::where('name', 'like', '%' . $this->term . '%')->paginate($this->limit);
        }

        if( $this->request->is('api*') || $this->request->wantsJson() )
        {
            return response()->json($result->artists);
        }

        $view = View::make('search.artist')
            ->with('result', $result)
            ->with('term', $this->term);

        if($this->request->ajax()) {
            $sections = $view->renderSections();

            if($this->request->input('page') && intval($this->request->input('page')) > 1)
            {
                return $sections['pagination'];
            } else {
                return $sections['content'];
            }
        }

        $item = new \stdClass();
        $item->term = $this->term;
        getMetatags($item);

        return $view;
    }

    public function album()
    {
        if(config('settings.automate') && ! $this->request->is('api*') && ! $this->request->wantsJson()) {
            if (intval($this->request->input('page')) < 2) {
                $albums = array();
                $data = (new Spotify)->searchAlbums($this->term)->get();
                foreach ($data['albums']['items'] as $item) {
                    $artists = handleSpotifyArtists($item['artists']);
                    if ($item['album_type'] != 'single') {
                        $albums[] = handleSpotifyAlbum($artists, $item);
                    }
                }
            }
        }

        $result = (Object) array();

        if(isset($albums) && count($albums)) {
            $result->albums = $albums;
        } else {
            $result->albums = Album::where('title', 'like', '%' . $this->term . '%')->paginate($this->limit);
        }

        if( $this->request->is('api*') || $this->request->wantsJson() )
        {
            return response()->json($result->albums);
        }

        $view = View::make('search.album')
            ->with('result', $result)
            ->with('term', $this->term);

        if($this->request->ajax()) {
            $sections = $view->renderSections();

            if($this->request->input('page') && intval($this->request->input('page')) > 1)
            {
                return $sections['pagination'];
            } else {
                return $sections['content'];
            }
        }

        $item = new \stdClass();
        $item->term = $this->term;
        getMetatags($item);

        return $view;
    }

    public function playlist()
    {
        if(config('settings.automate') && ! $this->request->is('api*') && ! $this->request->wantsJson()) {
            if (intval($this->request->input('page')) < 2) {
                $playlists = array();
                $data = (new Spotify)->searchPlaylists($this->term)->get();
                foreach ($data['playlists']['items'] as $item) {
                    $playlists[] = handleSpotifyPlaylist($item);
                }
            }
        }

        $result = (Object) array();

        if(isset($playlists) && count($playlists)) {
            $result->playlists = $playlists;
        } else {
            $result->playlists = Playlist::where('title', 'like', '%' . $this->term . '%')->paginate($this->limit);
        }

        if( $this->request->is('api*') || $this->request->wantsJson() )
        {
            return response()->json($result->playlists);
        }

        $view = View::make('search.playlist')
            ->with('result', $result)
            ->with('term', $this->term);

        if($this->request->ajax()) {
            $sections = $view->renderSections();

            if($this->request->input('page') && intval($this->request->input('page')) > 1)
            {
                return $sections['pagination'];
            } else {
                return $sections['content'];
            }
        }

        $item = new \stdClass();
        $item->term = $this->term;
        getMetatags($item);

        return $view;
    }

    public function user()
    {
        $result = (Object) array();
        $result->users = User::where('name', 'like', '%' . $this->term . '%')->paginate($this->limit);

        if( $this->request->is('api*') || $this->request->wantsJson() )
        {
            return response()->json($result->users);
        }

        $view = View::make('search.user')
            ->with('result', $result)
            ->with('term', $this->term);

        if($this->request->ajax()) {
            $sections = $view->renderSections();

            if($this->request->input('page') && intval($this->request->input('page')) > 1)
            {
                return $sections['pagination'];
            } else {
                return $sections['content'];
            }
        }

        $item = new \stdClass();
        $item->term = $this->term;
        getMetatags($item);

        return $view;
    }

    public function event()
    {
        $result = (Object) array();
        $result->events = Event::where('title', 'like', '%' . $this->term . '%')->paginate($this->limit);

        if( $this->request->is('api*') || $this->request->wantsJson() )
        {
            return response()->json($result->events);
        }

        $view = View::make('search.event')
            ->with('result', $result)
            ->with('term', $this->term);

        if($this->request->ajax()) {
            $sections = $view->renderSections();

            if($this->request->input('page') && intval($this->request->input('page')) > 1)
            {
                return $sections['pagination'];
            } else {
                return $sections['content'];
            }
        }

        $item = new \stdClass();
        $item->term = $this->term;
        getMetatags($item);

        return $view;
    }

    public function station()
    {
        $result = (Object) array();

        $result->stations = Station::where('title', 'like', '%' . $this->term . '%')->paginate($this->limit);

        if( $this->request->is('api*') || $this->request->wantsJson() )
        {
            return response()->json($result->stations);
        }

        $view = View::make('search.station')
            ->with('result', $result)
            ->with('term', $this->term);

        if($this->request->ajax()) {
            $sections = $view->renderSections();

            if($this->request->input('page') && intval($this->request->input('page')) > 1)
            {
                return $sections['pagination'];
            } else {
                return $sections['content'];
            }
        }

        $item = new \stdClass();
        $item->term = $this->term;
        getMetatags($item);

        return $view;
    }

    public function podcast()
    {
        if(env('AUTO_PODCAST') == 'true' && ! $this->request->is('api*') && ! $this->request->wantsJson()) {
            if (intval($this->request->input('page')) < 2) {
                $podcasts = array();

                $url = "https://itunes.apple.com/search?media=podcast&entity=podcast&attribute=titleTerm&limit=50&term=" . $this->term;

                $response = Http::withHeaders([])->get($url);
                if($response->successful()) {
                    foreach(json_decode($response->body())->results as $item) {
                        if(isset($item->feedUrl)) {
                            $podcast = new \stdClass();
                            $podcast->id = $item->trackId;
                            $podcast->artist = null;
                            $podcast->title = $item->trackName;
                            $podcast->feedUrl = $item->feedUrl;
                            $podcast->artwork_url = $item->artworkUrl100;
                            $podcast->permalink_url = route('frontend.podcast', ['id' => $item->trackId, 'slug' => str_slug($item->trackName) ? str_slug($item->trackName) : $item->trackName]);
                            $podcasts[] = $podcast;
                            dispatch(new \App\Jobs\SavePodcastDetails($item));
                        }
                    }
                }

            }
        }

        $result = (Object) array();

        if(isset($podcasts) && count($podcasts)) {
            $result->podcasts = $podcasts;
        } else {
            $result->podcasts = Podcast::where('title', 'like', '%' . $this->term . '%')->paginate($this->limit);
        }

        if( $this->request->is('api*') || $this->request->wantsJson() )
        {
            return response()->json($result->podcasts);
        }

        $view = View::make('search.podcast')
            ->with('result', $result)
            ->with('term', $this->term);

        if($this->request->ajax()) {
            $sections = $view->renderSections();

            if($this->request->input('page') && intval($this->request->input('page')) > 1)
            {
                return $sections['pagination'];
            } else {
                return $sections['content'];
            }
        }

        $item = new \stdClass();
        $item->term = $this->term;
        getMetatags($item);

        return $view;
    }

    public function suggest(){
        $songs = Song::where('title', 'like', '%' . $this->term . '%')->limit($this->limit)->get();
        $artists = Artist::where('name', 'like', '%' . $this->term . '%')->limit($this->limit)->get();
        $albums = Album::where('title', 'like', '%' . $this->term . '%')->limit($this->limit)->get();
        $playlists = Playlist::with('user')->where('title', 'like', '%' . $this->term . '%')->limit($this->limit)->get();
        $stations = Station::where('title', 'like', '%' . $this->term . '%')->limit($this->limit)->get();
        $users = User::where('name', 'like', '%' . $this->term . '%')->limit($this->limit)->get();

        if( $this->request->is('api*') )
        {
            return response()->json([
                'songs' => $songs,
                'artists' => $artists,
                'albums'=> $albums,
                'playlists' => $playlists,
                'stations' => $stations,
                'users' => $users,
            ]);
        }
    }

    public function city()
    {
        $cities = City::where('name', 'like', '%' . $this->term . '%')->paginate(20);

        if( $this->request->is('api*') || $this->request->wantsJson() )
        {
            return response()->json($cities);
        }
    }

    public function video()
    {
        $result = (Object) array();

        $result->videos = \App\Models\Video::where('title', 'like', '%' . $this->term . '%')->paginate(20);

        if( $this->request->is('api*') )
        {
            return response()->json($result->videos);
        }

        $result->playlists = Playlist::where('title', 'like', '%' . $this->term . '%')->paginate($this->limit);
        $result->artists = Artist::where('name', 'like', '%' . $this->term . '%')->paginate($this->limit);
        $result->albums = Album::where('title', 'like', '%' . $this->term . '%')->paginate($this->limit);
        $result->playlists = Playlist::where('title', 'like', '%' . $this->term . '%')->paginate($this->limit);
        $result->users = User::where('name', 'like', '%' . $this->term . '%')->paginate($this->limit);
        $result->events = Event::where('title', 'like', '%' . $this->term . '%')->paginate($this->limit);

        $view = View::make('search.podcast')
            ->with('result', $result)
            ->with('term', $this->term);

        if($this->request->ajax()) {
            $sections = $view->renderSections();

            if($this->request->input('page') && intval($this->request->input('page')) > 1)
            {
                return $sections['pagination'];
            } else {
                return $sections['content'];
            }
        }

        $item = new \stdClass();
        $item->term = $this->term;
        getMetatags($item);

        return $view;
    }
}