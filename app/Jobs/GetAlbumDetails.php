<?php

namespace App\Jobs;

use App\Models\AlbumSong;
use App\Models\Artist;
use App\Models\Song;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Modules\Spotify\Spotify;


class GetAlbumDetails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $album;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($album)
    {
        $this->album = $album;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $row = \App\Models\AlbumLog::where('album_id', $this->album->id)->first();
        if(isset($row->spotify_id) && ! $row->fetched) {
            $data = (new Spotify)->album($row->spotify_id)->get();
            foreach ($data['tracks']['items'] as $item) {
                $artists = handleSpotifyArtists($item['artists']);
                $song = handleSpotifySong($item, $artists, $this->album->artwork_url);

                $album_song = new AlbumSong;
                $album_song->song_id = $song->id;
                $album_song->album_id = $this->album->id;
                $album_song->priority = $item['track_number'];
                $album_song->save();

            }
            \App\Models\AlbumLog::where('album_id', $this->album->id)->update(['fetched' => 1]);
            sleep(1);
        }
    }
}
