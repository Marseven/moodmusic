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

class SaveArtistDetails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $artist;
    protected $unique_artist_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($artist, $unique_artist_id)
    {
        $this->artist = $artist;
        $this->unique_artist_id = $unique_artist_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::table('artists')->insertOrIgnore([
            [
                'id' => $this->unique_artist_id,
                'name' => $this->artist['name'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        DB::table('artist_spotify_logs')->insertOrIgnore([
            [
                'spotify_id' => $this->artist['id'],
                'artist_id' => $this->unique_artist_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
