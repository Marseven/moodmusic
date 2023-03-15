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

class SaveSongDetails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $song;
    protected $unique_song_id;
    protected $artistIds;
    protected $artwork_url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($song, $unique_song_id, $artistIds, $artwork_url)
    {
        $this->song = $song;
        $this->unique_song_id = $unique_song_id;
        $this->artistIds = $artistIds;
        $this->artwork_url = $artwork_url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::table('songs')->insertOrIgnore([
            [
                'id' => $this->unique_song_id,
                'title' => $this->song['name'],
                'artistIds' => implode(',', $this->artistIds),
                'duration' => intval($this->song['duration_ms']/1000),
                'explicit' => boolval($this->song['explicit']),
                'approved' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        DB::table('song_spotify_logs')->insertOrIgnore([
            [
                'spotify_id' => $this->song['id'],
                'song_id' => $this->unique_song_id,
                'artwork_url' => $this->artwork_url ? $this->artwork_url : (isset($this->song['album']['images'][1]) ? $this->song['album']['images'][1]['url'] : null),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
