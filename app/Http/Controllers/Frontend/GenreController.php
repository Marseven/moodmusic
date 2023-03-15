<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-30
 * Time: 10:08
 */

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use View;
use App\Models\Genre;
use App\Models\Song;
use App\Models\Album;
use App\Models\Playlist;
use App\Models\Artist;
use App\Models\Slide;
use App\Models\Channel;
use MetaTag;
use App\Modules\Spotify\Spotify;
use App\Modules\Spotify\SpotifySeed;

class GenreController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    private function getGenre(){
        $genre = Genre::where('alt_name',  $this->request->route('slug'))->firstOrFail();
        /** set metatags for genre section */
        MetaTag::set('title', $genre->meta_title ? $genre->meta_title : $genre->name);
        MetaTag::set('description', $genre->meta_description ? $genre->meta_description : $genre->description);
        MetaTag::set('keywords', $genre->meta_keywords);
        MetaTag::set('image', $genre->artwork);

        return $genre;
    }

    public function index()
    {
        $genre = $this->getGenre();

        if(config('settings.automate') && ! $this->request->is('api*')) {
            if (intval($this->request->input('page')) < 2) {
                $songs = array();
                $seed = (new SpotifySeed)->addGenres($genre->alt_name == 'rb' ? 'r-n-b' : $genre->alt_name);
                $data = (new Spotify)->recommendations($seed)->get();
                foreach ($data['tracks'] as $item) {
                    $artists = handleSpotifyArtists($item['album']['artists']);
                    if ($item['album']['album_type'] != 'single') {
                        handleSpotifyAlbum($artists, $item['album']);
                    }
                    $songs[] = handleSpotifySong($item, $artists);
                }
            }
        }

        $channels = Channel::where('genre', 'REGEXP', '(^|,)(' . $genre->id . ')(,|$)')->orderBy('priority', 'asc')->get();
        $slides = Slide::where('genre', 'REGEXP', '(^|,)(' . $genre->id . ')(,|$)')->orderBy('priority', 'asc')->get();

        if(isset($songs) && count($songs)) {
            $genre->songs = $songs;
        } else {
            $genre->songs = Song::where('genre', 'REGEXP', '(^|,)(' . $genre->id . ')(,|$)')->paginate(20);

            if(! isset($genre->songs) || ! count($genre->songs)) {
                $artists = Artist::where('genre', 'REGEXP', '(^|,)(' . $genre->id . ')(,|$)')->paginate(20);
                foreach ($artists as $key => $artist) {
                    $genre->songs[$key] = $artist->songs()->first();
                }
            }
        }

        if( $this->request->is('api*') )
        {
            return response()->json(array(
                'slides' => json_decode(json_encode($slides)),
                'channels' => json_decode(json_encode($channels)),
                'genre' => $genre,
            ));
        }

        $genre->related = Genre::where('id', '!=',  $genre->id);

        $view = View::make('genre.index')
            ->with('slides', json_decode(json_encode($slides)))
            ->with('channels', json_decode(json_encode($channels)))
            ->with('genre', $genre);

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

    public function songs()
    {
        $genre = $this->getGenre();
        $songs = Song::where('genre', 'REGEXP', '(^|,)(' . $genre->id . ')(,|$)');

        if( $this->request->is('api*') )
        {
            return response()->json($songs);
        }
    }

    public function albums()
    {
        $genre = $this->getGenre();
        $albums = Album::where('genre', 'REGEXP', '(^|,)(' . $genre->id . ')(,|$)');

        if( $this->request->is('api*') )
        {
            return response()->json($albums);
        }
    }

    public function artists()
    {
        $genre = $this->getGenre();
        $artists = Artist::where('genre', 'REGEXP', '(^|,)(' . $genre->id . ')(,|$)');

        if( $this->request->is('api*') )
        {
            return response()->json($artists);
        }
    }

    public function playlists()
    {
        $genre = $this->getGenre();
        $playlists = Playlist::where('genre', 'REGEXP', '(^|,)(' . $genre->id . ')(,|$)');

        if( $this->request->is('api*') )
        {
            return response()->json($playlists);
        }
    }
}