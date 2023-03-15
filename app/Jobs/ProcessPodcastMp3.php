<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


use Illuminate\Support\Str;
use Response;
use DB;
use Config;
use Image;
use Storage;
use Validator;
use FFMpeg;
use File;
use Cache;

class ProcessPodcastMp3 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $model;
    protected $audio;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($model, $audio)
    {
        $this->model = $model;
        $this->audio = $audio;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tempFile = Str::random(10) . '.mp3';

        $ffmpeg = FFMpeg\FFMpeg::create([
            'ffmpeg.binaries' => env('FFMPEG_PATH', '/usr/bin/ffmpeg'),
            'ffprobe.binaries' => env('FFPROBE_PATH', '/usr/bin/ffprobe'),
            'timeout' => 3600,
            'ffmpeg.threads' => 12,
        ]);

        $audio = $ffmpeg->open($this->audio->path);
        $audio->save((new FFMpeg\Format\Audio\Mp3())->setAudioKiloBitrate(intval(config('settings.podcast_audio_default_bitrate', 128))), Storage::disk('public')->path($tempFile));
        $this->model->addMedia(Storage::disk('public')->path($tempFile))
            ->usingFileName($tempFile, PATHINFO_FILENAME)
            ->withCustomProperties(['bitrate' => intval(config('settings.podcast_audio_default_bitrate', 128))])
            ->toMediaCollection('audio', config('settings.storage_audio_location', 'public'));

        $this->model->mp3 = 1;
        $this->model->pending = 0;
        @unlink($this->audio->path);

        $this->model->save();
    }
}
