<?php

namespace App\Jobs;

use App\Models\Album;
use App\Models\Artist;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Modules\Spotify\Spotify;
use DB;

class ImportAlbum implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = (new Spotify)->album($this->id)->get();
        if(! \App\Models\AlbumLog::where('spotify_id', $this->id)->exists()) {
            $album = new Album();
            $album->title = $data['name'];
            foreach ($data['artists'] as $artist_item) {
                $row = Artist::where('name', $artist_item['name'])->first();
                if (isset($row->id)) {
                    $artists[] = $row->id;
                } else {
                    $artist = new Artist();
                    $artist->name = $artist_item['name'];
                    $artist->save();
                    $artists[] = $artist->id;

                    DB::table('artist_spotify_logs')->insertOrIgnore([
                        [
                            'spotify_id' => $artist_item['id'],
                            'artist_id' => $artist->id,
                            'artwork_url' => null,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                    ]);
                }
            }

            $album->released_at = Carbon::parse($data['release_date']);
            $album->artistIds = implode(',', $artists);
            $album->approved = 1;

            try {
                if(isset($data['images'][1]['url'])) {
                    $album->addMediaFromUrl($data['images'][1]['url'])
                        ->usingFileName(time(). '.jpg')
                        ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));

                }
            } catch (\Exception $e) {

            }

            $album->save();

            DB::table('album_spotify_logs')->insertOrIgnore([
                [
                    'spotify_id' => $data['id'],
                    'album_id' => $album->id,
                    'artwork_url' => isset($data['images'][1]) ? $data['images'][1]['url'] : null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]);

            foreach ($data['tracks']['items'] as $item) {
                dispatch(new ImportSong($item['id'], $album->id));
            }
        }
    }
}
