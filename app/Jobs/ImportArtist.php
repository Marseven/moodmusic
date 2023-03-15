<?php

namespace App\Jobs;

use App\Models\Artist;
use App\Models\Genre;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Modules\Spotify\Spotify;
use DB;

class ImportArtist implements ShouldQueue
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
        if(! \App\Models\ArtistLog::where('spotify_id', $this->id)->exists()) {
            $data = (new Spotify)->artist($this->id)->get();

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

            $artist = new Artist();
            $artist->name = $data['name'];

            if(isset($genres)) {
                $artist->genre = implode(',', $genres);
            }

            $artist->save();

            DB::table('artist_spotify_logs')->insertOrIgnore([
                [
                    'spotify_id' => $this->id,
                    'artist_id' => $artist->id,
                    'artwork_url' => isset($data['images'][0]) ? $data['images'][0]['url'] : null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]);

        } else {
            $dataAlbums = (new Spotify)->artistAlbums($this->id)->get();
            foreach($dataAlbums['items'] as $item) {
                dispatch(new ImportAlbum($item['id']));
            }
        }
    }
}
