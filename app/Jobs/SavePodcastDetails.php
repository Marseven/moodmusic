<?php

namespace App\Jobs;

use App\Models\Podcast;
use App\Models\PodcastCategory;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DB;

class SavePodcastDetails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $item;
    protected $artistIds;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($item, $artistIds = null)
    {
        $this->item = $item;
        $this->artistIds = $artistIds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::table('podcasts')->insertOrIgnore([
            [
                'id' => $this->item->trackId,
                'title' => $this->item->trackName,
                'rss_feed_url' => $this->item->feedUrl,
                'country_code' => $this->item->country,
                'explicit' => $this->item->trackExplicitness != 'cleaned' ? 1 : 0,
                'created_at' => Carbon::parse($this->item->releaseDate),
                'updated_at' => Carbon::now(),
            ],
        ]);

        $podcast = Podcast::findOrFail($this->item->trackId);

        if(!$podcast->category) {
            $artist = \App\Models\Artist::where('name', $this->item->artistName)->first();
            if(isset($artist->id)) {
                $podcast->artist_id = $artist->id;
            } else {
                $artist = new \App\Models\Artist();
                $artist->name = $this->item->artistName;
                $artist->save();
                $podcast->artist_id = $artist->id;
            }

            try {
                $podcast->addMediaFromUrl($this->item->artworkUrl600)
                    ->usingFileName(time(). '.jpg')
                    ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
            } catch (\Exception $exception) {
                // do nothing
            }

            if(isset($this->item->genres) && count($this->item->genres)) {
                $genres = array();
                foreach($this->item->genres as $name) {
                    $genre_row = PodcastCategory::where('alt_name', str_slug($name))->first();
                    if(isset($genre_row->id)) {
                        $genres[] = $genre_row->id;
                    } else {
                        $genre = new PodcastCategory();
                        $genre->name = $name;
                        $genre->alt_name = str_slug($name);
                        $genre->save();
                        $genres[] = $genre->id;
                    }
                }
            }

            $podcast->category = implode(',', $genres);
            $podcast->save();

            /*
            $job = new \App\Jobs\SavePodcastEpisodes($podcast);
            $job->delay(Carbon::now()->addSeconds(30));
            dispatch($job);
            */
        }
    }
}
