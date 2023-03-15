<?php

namespace App\Jobs;

use App\Models\Episode;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DB;

class SavePodcastEpisodes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $podcast;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($podcast)
    {
        $this->podcast = $podcast;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        @libxml_use_internal_errors(true);
        $rss = @simplexml_load_file($this->podcast->rss_feed_url);

        if (false !== $rss) {

            if (isset($rss->channel)) {
                if(! $this->podcast->description) {
                    $this->podcast->description = strip_tags($rss->channel->description);
                }

                $this->podcast->updated_at = Carbon::parse($rss->channel->lastBuildDate);
                $this->podcast->save();
            }

            if (isset($rss->channel->item)) {
                foreach ($rss->channel->item as $item) {
                    if (!Episode::where('created_at', Carbon::parse($item->pubDate))->where('podcast_id', $this->podcast->id)->exists()) {
                        $episode = new Episode();
                        $episode->podcast_id = $this->podcast->id;
                        $episode->title = $item->title;
                        $episode->description = strip_tags($item->description);
                        $episode->created_at = Carbon::parse($item->pubDate);
                        $episode->type = $item->enclosure['type'];
                        $episode->stream_url = $item->enclosure['url'];
                        $itunes = $item->children('http://www.itunes.com/dtds/podcast-1.0.dtd');
                        $episode->type = 1;
                        $episode->duration = intval(reset($itunes->duration));
                        $episode->explicit = (reset($itunes->explicit) == 'clean' || reset($itunes->explicit) == 'no' ) ? 0 : 1;
                        $episode->save();
                    }
                }
            }
        }
    }
}
