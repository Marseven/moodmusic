<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-28
 * Time: 15:13
 */

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use View;
use App\Models\Artist;
use Auth;
use App\Modules\Spotify\Spotify;

class ArtistController extends Controller
{
    private $request;
    private $artist;

    public function __construct(Request $request)
    {
        $this->request = $request;

    }
    public function index()
    {
        $this->artist = Artist::findOrFail($this->request->route('id'));

        if($this->request->is('api*') || $this->request->wantsJson())
        {
            $this->artist->setRelation('songs', $this->artist->songs()->paginate(20));
            $this->artist->setRelation('podcasts', $this->artist->podcasts()->paginate(20));
            $this->artist->setAppends(['song_count', 'genres', 'moods', 'artwork_url', 'album_count']);
            $this->artist->follower_count = $this->artist->followers()->count();

            if($this->request->get('callback'))
            {
                return response()->jsonp($this->request->get('callback'), $this->artist->songs)->header('Content-Type', 'application/javascript');
            }

            return response()->json(array(
                'artist' => $this->artist
            ));
        } else {

            if(config('settings.automate')) {
                $row = \App\Models\ArtistLog::where('artist_id', $this->artist->id)->first();
                if(isset($row->spotify_id) && ! $row->fetched) {
                    $data = (new Spotify)->artist($row->spotify_id)->get();

                    $genres = array();
                    if(isset($data['genres']) && count($data['genres'])) {
                        foreach($data['genres'] as $name) {
                            $genre_row = Genre::where('alt_name', str_slug($name))->first();
                            if(isset($genre_row->id)) {
                                $genres[] = $genre_row->id;
                            } else {
                                $genre = new Genre();
                                $genre->name = $name;
                                $genre->alt_name = str_slug($name);
                                $genre->discover = 0;
                                $genre->save();
                                $genres[] = $genre->id;
                            }
                        }
                    }

                    $this->artist->genre = implode(',', $genres);
                    $this->artist->save();

                    $dataAlbums = (new Spotify)->artistAlbums($row->spotify_id)->get();

                    $albums = array();
                    foreach($dataAlbums['items'] as $item) {
                        $artists = handleSpotifyArtists($item['artists']);
                        $albums[] = handleSpotifyAlbum($artists, $item);
                    }
                    $this->artist->setRelation('albums', array_slice($albums, 0, 4));

                    $songs = array();
                    $dataSongs = (new Spotify)->artistTopTracks($row->spotify_id)->get();
                    foreach($dataSongs['tracks'] as $item) {
                        $artists = handleSpotifyArtists($item['album']['artists']);
                        //handleSpotifyAlbum($artists, $item['album']);
                        $songs[] = handleSpotifySong($item, $artists);
                    }
                    $this->artist->setRelation('songs', $songs);

                    \App\Models\ArtistLog::where('artist_id', $this->artist->id)->update(['fetched' => 1]);

                    $this->artist->setRelation('podcasts', $this->artist->podcasts()->paginate(20));
                    $this->artist->setRelation('activities', $this->artist->activities()->latest()->paginate(10));
                    $this->artist->setRelation('similar', $this->artist->similar()->paginate(5));

                } else {
                    $this->artist->setRelation('albums', $this->artist->albums()->latest()->limit(4)->get());
                    $this->artist->setRelation('songs', $this->artist->songs()->paginate(20));
                    $this->artist->setRelation('podcasts', $this->artist->podcasts()->paginate(20));
                    $this->artist->setRelation('activities', $this->artist->activities()->latest()->paginate(10));
                    $this->artist->setRelation('similar', $this->artist->similar()->paginate(5));
                }
            } else {
                $this->artist->setRelation('albums', $this->artist->albums()->latest()->limit(4)->get());
                $this->artist->setRelation('songs', $this->artist->songs()->paginate(20));
                $this->artist->setRelation('podcasts', $this->artist->podcasts()->paginate(20));
                $this->artist->setRelation('activities', $this->artist->activities()->latest()->paginate(10));
                $this->artist->setRelation('similar', $this->artist->similar()->paginate(5));
            }
        }

        $view = View::make('artist.index')
            ->with('artist', $this->artist);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            if($this->request->input('page') && intval($this->request->input('page')) > 1)
            {
                return $sections['pagination'];
            } else {
                return $sections['content'];
            }
        }

        getMetatags($this->artist);

        return $view;
    }

    public function albums()
    {
        $this->artist = Artist::findOrFail($this->request->route('id'));
        $this->artist->setRelation('albums', $this->artist->albums()->paginate(20));

        if( $this->request->is('api*') )
        {
            return response()->json(array(
                'artist' => $this->artist
            ));
        }

        $view = View::make('artist.albums')->with('artist', $this->artist);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            if($this->request->input('page') && intval($this->request->input('page')) > 1)
            {
                return $sections['pagination'];
            } else {
                return $sections['content'];
            }
        }

        getMetatags($this->artist);

        return $view;
    }

    public function podcasts()
    {
        $this->artist = Artist::findOrFail($this->request->route('id'));
        $this->artist->setRelation('podcasts', $this->artist->podcasts()->paginate(20));

        $view = View::make('artist.podcasts')->with('artist', $this->artist);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags($this->artist);

        return $view;
    }

    public function similar()
    {
        $this->artist = Artist::findOrFail($this->request->route('id'));
        $this->artist->setRelation('similar', $this->artist->similar()->paginate(20));

        if( $this->request->is('api*') )
        {
            return response()->json(array(
                'artist' => $this->artist
            ));
        }

        $view = View::make('artist.similar-artists')
            ->with('artist', $this->artist)
            ->with('similar', $this->artist->similar);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags($this->artist);

        return $view;
    }

    public function followers()
    {
        $this->artist = Artist::with('followers')->findOrFail($this->request->route('id'));

        if( $this->request->is('api*') )
        {
            return response()->json(array(
                'artist' => $this->artist
            ));
        }

        $view = View::make('artist.followers')->with('artist', $this->artist);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags($this->artist);

        return $view;
    }

    public function events()
    {
        $this->artist = Artist::with('followers')->findOrFail($this->request->route('id'));

        if( $this->request->is('api*') )
        {
            return response()->json(array(
                'artist' => $this->artist
            ));
        }

        $view = View::make('artist.events')->with('artist', $this->artist);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags($this->artist);

        return $view;
    }

    public function spotifyArtwork()
    {
        $artist = Artist::findOrFail($this->request->route('id'));
        if($artist->log) {
            $data = (new Spotify)->artist($artist->log->spotify_id)->get();

            if(isset($data['genres']) && count($data['genres'])) {
                $genres = array();
                foreach($data['genres'] as $name) {
                    $genre_row = Genre::where('alt_name', str_slug($name))->first();
                    if(isset($genre_row->id)) {
                        $genres[] = $genre_row->id;
                    } else {
                        $genre = new Genre();
                        $genre->name = $name;
                        $genre->alt_name = str_slug($name);
                        $genre->discover = 0;
                        $genre->save();
                        $genres[] = $genre->id;
                    }
                }
            }

            if(isset($genres) && is_array($genres)) {
                $artist->genre = implode(',', $genres);
            }

            $artist->save();

            if(isset($data['images'][1])){


                $artist->log->artwork_url = $data['images'][1]['url'];
                $artist->log->save();

                header("Location: " . $data['images'][1]['url']);

                exit;
            } else {
                $artist->addMedia(public_path( 'common/default/artist.png'))
                    ->preservingOriginal()
                    ->usingFileName(time(). '.jpg')
                    ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
                $artist->save();

                header("Location: " . asset( 'common/default/artist.png'));
                exit;
            }
        } else {
            header("Location: " . asset( 'common/default/artist.png'));
            exit;
        }
    }
}