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


class ProcessPodcastHLS implements ShouldQueue
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
        $tempFolder = Str::random(32);
        Storage::disk('public')->makeDirectory($tempFolder);
        $tempFile = Str::random(32);

        if(config('settings.podcast_audio_hls_drm')) {
            exec(env('FFMPEG_PATH', '/usr/bin/ffmpeg') . ' -i ' . $this->audio->path . ' -c:a aac -b:a ' . intval(config('settings.podcast_audio_default_bitrate', 128)) . 'k -vn -hls_list_size 0 -hls_time 20 -hls_key_info_file ' . public_path('enc.keyinfo') . ' ' . Storage::disk('public')->path($tempFolder . '/' . $tempFile. '.m3u8'), $status, $var);
        } else {
            exec(env('FFMPEG_PATH', '/usr/bin/ffmpeg') . ' -i ' . $this->audio->path . ' -c:a aac -b:a ' . intval(config('settings.podcast_audio_default_bitrate', 128)) . 'k -vn -hls_list_size 0 -hls_time 20 ' . Storage::disk('public')->path($tempFolder . '/' . $tempFile. '.m3u8'), $status, $var);
        }

        if(intval($var) == 0) {
            foreach (File::allFiles(Storage::disk('public')->path($tempFolder)) as $file) {
                if(ends_with($file, ['.ts'])){
                    $this->model->addMediaFromDisk($tempFolder . '/' . trim(basename($file).PHP_EOL), 'public')->toMediaCollection('hls', config('settings.storage_audio_location', 'public'));
                }
            }
            $this->model->hls = 1;
            $this->model->addMedia(Storage::disk('public')->path($tempFolder . '/' . $tempFile. '.m3u8'))
                ->withCustomProperties(['bitrate' => config('settings.podcast_audio_default_bitrate', 128)])
                ->toMediaCollection('m3u8', config('settings.storage_audio_location', 'public'));
            //Delete the temp file
            $this->model->save();
            Storage::disk('public')->deleteDirectory($tempFolder, true);
            sleep(1);
            Storage::disk('public')->deleteDirectory($tempFolder);

            $this->model->pending = 0;
            @unlink($this->audio->path);

            $this->model->save();
        } else {
            abort(500, 'FFMPEG HLS conversion has failed!');
        }
    }
}
