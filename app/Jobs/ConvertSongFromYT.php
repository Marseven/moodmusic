<?php

namespace App\Jobs;

use App\Models\Song;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Modules\Spotify\Spotify;
use DB;
use Storage;
use Log;

class ConvertSongFromYT implements ShouldQueue
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
        $song = Song::withoutGlobalScopes()->findOrFail($this->id);
        if(! $song->mp3 && ! $song->hls) {
            if(isset($song->log) && isset($song->log->youtube)) {
                $tempPath = Str::random(32);
                if(config('settings.import_youtube_library', 0) == 1) {
                    if(@shell_exec(config('settings.youtube_dl_path', '/usr/local/bin/youtube-dl') . ' -o "' . Storage::disk('public')->path($tempPath) . '" -f m4a https://www.youtube.com/watch?v=' . $song->log->youtube)) {
                        $this->processAudio($song, $tempPath);
                    }
                } else {
                    $info = new \App\Modules\YoutubeDownloader\YouTubeDownloader();
                    $links = collect($info->getDownloadLinks($song->log->youtube))->first();
                    $m4a = collect($links)->firstWhere('itag', '140');

                    if(file_put_contents(Storage::disk('public')->path($tempPath), file_get_contents($m4a->url))) {
                        $this->processAudio($song, $tempPath);
                    }
                }
            } else {
                $data = app('App\Http\Controllers\Frontend\StreamController')->youtube($song->id, false);
                $videos = $data->getData()->items;

                if(count($videos)) {
                    $tempPath = Str::random(32);
                    if(config('settings.import_youtube_library', 0) == 1) {
                        if(@shell_exec(config('settings.youtube_dl_path', '/usr/local/bin/youtube-dl') . ' -o "' . Storage::disk('public')->path($tempPath) . '" -f m4a https://www.youtube.com/watch?v=' . $videos[0]->id->videoId)) {
                            $this->processAudio($song, $tempPath);
                        }
                    } else {
                        $info = new \App\Modules\YoutubeDownloader\YouTubeDownloader();
                        $links = collect($info->getDownloadLinks($videos[0]->id->videoId))->first();
                        $m4a = collect($links)->firstWhere('itag', '140');

                        if(file_put_contents(Storage::disk('public')->path($tempPath), file_get_contents($m4a->url))) {
                            $this->processAudio($song, $tempPath);
                        }
                    }
                }
            }
        }
    }

    public function processAudio($song, $tempPath) {

            $getID3 = new \getID3;
            $mp3Info = $getID3->analyze(Storage::disk('public')->path($tempPath));

            $data = [
                'bitRate' => intval($mp3Info['audio']['bitrate'] / 1000),
                'playtimeSeconds' => intval($mp3Info['playtime_seconds'])
            ];

            $song->duration = $data['playtimeSeconds'];

            if(config('settings.ffmpeg') && @shell_exec(env('FFMPEG_PATH') . ' -version')) {

                $audio = new \stdClass();
                $audio->path = Storage::disk('public')->path($tempPath);
                $audio->original_name = $song->title;
                $audio->bitrate = $data['bitRate'];

                if(config('settings.audio_mp3_preview')) {
                    dispatch(new ProcessPreview($song, $audio));
                }

                $song->pending = 1;
                $song->save();

                if(! config('settings.audio_stream_hls') || config('settings.audio_stream_hls') && config('settings.audio_mp3_backup')) {
                    dispatch(new ProcessMp3($song, $audio));

                    if (config('settings.audio_hd', false) && $data['bitRate'] >= config('settings.audio_hd_bitrate', 320)) {
                        dispatch(new ProcessMp3($song, $audio, true));
                    }
                }

                if(config('settings.audio_stream_hls')) {
                    dispatch(new ProcessHLS($song, $audio));
                    if(config('settings.audio_hd', false) && $data['bitRate'] >= config('settings.audio_hd_bitrate', 320)) {
                        dispatch(new ProcessHLS($song, $audio, true));
                    }
                }

            } else {
                $song->addMedia(Storage::disk('public')->path($tempPath))
                    ->usingFileName(Str::random(10) . '.mp3', PATHINFO_FILENAME)
                    ->withCustomProperties(['bitrate' => $data['bitRate']])
                    ->toMediaCollection('audio', config('settings.storage_audio_location', 'public'));
                $song->mp3 = 1;
                $song->save();
            }


        sleep(3);
    }
}
