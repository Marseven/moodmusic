<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Modules\Spotify\Spotify;
use DB;

class SavePlaylistDetails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $playlist;
    protected $unique_playlist_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($playlist, $unique_playlist_id)
    {
        $this->playlist = $playlist;
        $this->unique_playlist_id = $unique_playlist_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::table('playlists')->insertOrIgnore([
            [
                'id' => $this->unique_playlist_id,
                'title' => $this->playlist['name'],
                'description' => $this->playlist['description'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);


        DB::table('playlist_spotify_logs')->insertOrIgnore([
            [
                'spotify_id' => $this->playlist['id'],
                'playlist_id' => $this->unique_playlist_id,
                'artwork_url' => $this->playlist['images'][0]['url'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
