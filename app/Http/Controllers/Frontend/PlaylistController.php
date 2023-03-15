<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-01
 * Time: 22:01
 */

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Jobs\GetPlaylistDetails;
use App\Models\PlaylistSong;
use Illuminate\Http\Request;
use View;
use App\Models\Playlist;
use App\Modules\Spotify\Spotify;

class PlaylistController extends Controller
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        if( $this->request->is('api*') || $this->request->wantsJson())
        {
            $playlist = Playlist::withoutGlobalScopes()->with('user')->findOrFail($this->request->route('id'))->makeVisible('genre')->makeVisible('mood');

            if(config('settings.automate')) {
                dispatch_now(new GetPlaylistDetails($playlist));
                sleep(1);
            }

            $playlist->setRelation('songs', $playlist->songs()->get());

            if($this->request->get('callback'))
            {
                return response()->jsonp($this->request->get('callback'), $playlist->songs)->header('Content-Type', 'application/javascript');
            }

            $playlist->setRelation('activities', $playlist->activities()->paginate(10));

            return response()->json($playlist);
        } else {

            $playlist = Playlist::withoutGlobalScopes()->with('user')->findOrFail($this->request->route('id'))->makeVisible('genre')->makeVisible('mood');

            if(! isset($playlist->id)) {
                abort(404);
            } elseif(auth()->check() && ! $playlist->visibility && ($playlist->user_id != auth()->user()->id)) {
                abort(404);
            }  elseif(! auth()->check() && ! $playlist->visibility) {
                abort(404);
            }

            if(config('settings.automate')) {
                $row = \App\Models\PlaylistLog::where('playlist_id', $playlist->id)->first();
                if(isset($row->spotify_id) && ! $row->fetched) {
                    $data = (new Spotify)->playlist($row->spotify_id)->get();
                    $songs = array();
                    foreach ($data['tracks']['items'] as $item) {
                        $item = $item['track'];
                        $artists = handleSpotifyArtists($item['artists']);
                        $song = handleSpotifySong($item, $artists);
                        $songs[] = $song;
                        $playlist_song = new PlaylistSong();
                        $playlist_song->song_id = $song->id;
                        $playlist_song->playlist_id = $playlist->id;
                        $playlist_song->priority = $item['track_number'];
                        $playlist_song->save();
                    }
                    $playlist->setRelation('songs', $songs);

                    \App\Models\PlaylistLog::where('playlist_id', $playlist->id)->update(['fetched' => 1]);
                } else {
                    $playlist->setRelation('songs', $playlist->songs()->get());
                }
            } else {
                $playlist->setRelation('songs', $playlist->songs()->get());
            }

            $playingDuration = 0;

            if(count($playlist->songs)){
                foreach ($playlist->songs as $song) {
                    $playingDuration = $playingDuration +  $song->duration;
                }
            }

            $playlist->playingDuration = humanTime($playingDuration);

            if(isset($playlist->user)) {
                $playlist->related = Playlist::with('user')
                    ->where('id', '!=', $playlist->id)
                    ->where('user_id', $playlist->user->id)->limit(5)->get();
            }
        }

        $view = View::make('playlist.index')
            ->with('playlist', $playlist);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags($playlist);

        return $view;
    }

    public function subscribers()
    {
        $playlist = Playlist::findOrFail($this->request->route('id'));
        $playlist->setRelation('subscribers', $playlist->subscribers()->paginate(20));


        if( $this->request->is('api*') )
        {
            return response()->json($playlist->subscribers);
        }

        $view = View::make('playlist.subscribers')
            ->with('playlist', $playlist);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags($playlist);

        return $view;
    }

    public function collaborators()
    {
        $playlist = Playlist::findOrFail($this->request->route('id'));
        $playlist->setRelation('collaborators', $playlist->collaborators()->paginate(20));

        if( $this->request->is('api*') )
        {
            return response()->json($playlist->collaborators);
        }

        $view = View::make('playlist.collaborators')
            ->with('playlist', $playlist);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags($playlist);

        return $view;
    }
}