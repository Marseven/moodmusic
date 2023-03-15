<?php


namespace App\Http\Controllers\Frontend;

use App\Jobs\GetAlbumDetails;
use App\Models\Artist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\Album;
use App\Models\Genre;
use DB;
use View;
use App\Models\Role;
use App\Modules\Spotify\Spotify;
use App\Models\AlbumSong;

class AlbumController
{
    private $request;
    private $album;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {

        if($this->request->is('api*') || $this->request->wantsJson())
        {
            $this->album = Album::withoutGlobalScopes()->findOrFail($this->request->route('id'));

            if(config('settings.automate')) {
                dispatch_now(new GetAlbumDetails($this->album));
                sleep(1);
            }

            $this->album->setRelation('songs', $this->album->songs()->withoutGlobalScopes()->get());

            if($this->request->get('callback'))
            {
                return response()->jsonp($this->request->get('callback'), $this->album->songs)->header('Content-Type', 'application/javascript');
            }
            return response()->json($this->album);
        }

        $this->album = Album::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        if(! $this->album->approved && auth()->check() && Role::getValue('admin_albums')) {

        } else {
            if(! isset($this->album->id)) {
                abort(404);
            } elseif(auth()->check() && ! $this->album->visibility && ($this->album->user_id != auth()->user()->id)) {
                abort(404);
            }  elseif(! auth()->check() && ! $this->album->visibility) {
                abort(404);
            } elseif(! $this->album->approved) {
                abort(404);
            }
        }

        if(config('settings.automate')) {
            $row = \App\Models\AlbumLog::where('album_id', $this->album->id)->first();
            if(isset($row->spotify_id) && ! $row->fetched) {
                $data = (new Spotify)->album($row->spotify_id)->get();

                $songs = array();

                foreach ($data['tracks']['items'] as $item) {
                    $artists = handleSpotifyArtists($item['artists']);
                    $song = handleSpotifySong($item, $artists, $this->album->artwork_url);
                    $songs[] = $song;

                    $album_song = new AlbumSong;
                    $album_song->song_id = $song->id;
                    $album_song->album_id = $this->album->id;
                    $album_song->priority = $item['track_number'];
                    $album_song->save();

                }

                $this->album->setRelation('songs', $songs);


                \App\Models\AlbumLog::where('album_id', $this->album->id)->update(['fetched' => 1]);
            } else {
                $this->album->setRelation('songs', $this->album->songs()->withoutGlobalScopes()->get());
            }
        } else {
            $this->album->setRelation('songs', $this->album->songs()->withoutGlobalScopes()->get());
        }

        $view = View::make('album.index')
            ->with('album',  $this->album);

        if(count($this->album->artists) == 1) {
            $artist = $this->album->artists->first();
            $artist->setRelation('songs', $artist->songs()->paginate(5));
            $artist->setRelation('similar', $artist->similar()->paginate(5));
            $artistTopSongs = $artist->songs;
            $view = $view->with('artistTopSongs', $artistTopSongs)->with('artist', $artist);
        }

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags($this->album);

        return $view;
    }

    public function related (){
        if(auth()->check() && Role::getValue('admin_albums')) {
            $album = Album::withoutGlobalScopes()->findOrFail($this->request->route('id'));
        } else {
            $album = Album::findOrFail($this->request->route('id'));
        }

        $related = Album::where('id', '!=', $album->id)->paginate(20);

        $view = View::make('album.related')
            ->with('album', $album)
            ->with('related', $related);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        return $view;
    }
}