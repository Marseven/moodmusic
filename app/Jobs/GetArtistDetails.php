<?php

namespace App\Jobs;

use App\Models\Album;
use App\Models\AlbumSong;
use App\Models\Artist;
use App\Models\Genre;
use App\Models\Song;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Modules\Spotify\Spotify;

class GetArtistDetails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $artist;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($artist)
    {
        $this->artist = $artist;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $row = \App\Models\ArtistLog::where('artist_id', $this->artist->id)->first();
        if(isset($row->spotify_id) && ! $row->fetched) {
            $data = (new Spotify)->artist($row->spotify_id)->get();
            //handle genre
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

            $dataAlbums = (new Spotify)->artistAlbums($row->spotify_id)->get();

            foreach($dataAlbums['items'] as $item) {
                $artists = handleSpotifyArtists($item['artists']);
                handleSpotifyAlbum($artists, $item);
            }

            $dataSongs = (new Spotify)->artistTopTracks($row->spotify_id)->get();
            foreach($dataSongs['tracks'] as $item) {
                $artists = handleSpotifyArtists($item['album']['artists']);
                //handleSpotifyAlbum($artists, $item['album']);
                handleSpotifySong($item, $artists);
            }
            \App\Models\ArtistLog::where('artist_id', $this->artist->id)->update(['fetched' => 1]);

            sleep(1);
        }
    }
}
