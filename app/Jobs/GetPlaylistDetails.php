<?php

namespace App\Jobs;

use App\Models\PlaylistSong;
use App\Models\Artist;
use App\Models\Song;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Modules\Spotify\Spotify;

class GetPlaylistDetails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $playlist;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($playlist)
    {
        $this->playlist = $playlist;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $row = \App\Models\PlaylistLog::where('playlist_id', $this->playlist->id)->first();
        if(isset($row->spotify_id) && ! $row->fetched) {
            $data = (new Spotify)->playlist($row->spotify_id)->get();
            foreach ($data['tracks']['items'] as $item) {
                $item = $item['track'];
                $artists = handleSpotifyArtists($item['artists']);
                $song = handleSpotifySong($item, $artists);

                $playlist_song = new PlaylistSong();
                $playlist_song->song_id = $song->id;
                $playlist_song->playlist_id = $this->playlist->id;
                $playlist_song->priority = $item['track_number'];
                $playlist_song->save();

            }
            \App\Models\PlaylistLog::where('playlist_id', $this->playlist->id)->update(['fetched' => 1]);
        }
    }
}
