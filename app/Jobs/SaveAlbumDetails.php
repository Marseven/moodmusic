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

class SaveAlbumDetails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $album;
    protected $unique_album_id;
    protected $artistIds;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($album, $unique_album_id, $artistIds)
    {
        $this->album = $album;
        $this->unique_album_id = $unique_album_id;
        $this->artistIds = $artistIds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::table('albums')->insertOrIgnore([
            [
                'id' => $this->unique_album_id,
                'title' => $this->album['name'],
                'artistIds' => implode(',', $this->artistIds),
                'released_at' => Carbon::parse($this->album['release_date']),
                'approved' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);


        DB::table('album_spotify_logs')->insertOrIgnore([
            [
                'spotify_id' => $this->album['id'],
                'album_id' => $this->unique_album_id,
                'artwork_url' => (isset($this->album['images']) && isset($this->album['images'][1])) ? $this->album['images'][1]['url'] : null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

    }
}
