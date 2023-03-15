<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-18
 * Time: 21:20
 */

namespace App\Http\Controllers\Frontend;

use App\Models\Episode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use File;
use DB;

class PodcastStreamController
{
    private $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function mp3(){
        $episode = Episode::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        if($episode->getFirstMedia('audio')->disk == 's3') {
            header("Location: " . $episode->getFirstTemporaryUrl(Carbon::now()->addMinutes(intval(config('settings.s3_signed_time', 5))), 'audio'));
            exit();
        } else {
            if(config('settings.direct_stream')) {
                header("Location: " . $episode->getFirstMedia('audio')->getUrl());
                exit();
            } else {
                header('Content-type: ' . $episode->getFirstMedia('audio')->mime_type);
                header('Content-Length: ' . $episode->getFirstMedia('audio')->size);
                header('Content-Disposition: attachment; filename="' . $episode->getFirstMedia('audio')->file_name);
                header('Cache-Control: no-cache');
                header('Accept-Ranges: bytes');

                if(config('filesystems.disks')[$episode->getFirstMedia('audio')->disk]['driver'] == 'local') {
                    readfile($episode->getFirstMedia('audio')->getPath());
                } else {
                    readfile($episode->getFirstMedia('audio')->getUrl());
                }
                exit();
            }
        }
    }

    public function hls(){
        $episode = Episode::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        if($episode->getFirstMedia('hls')->disk == 's3') {
            $content = stream_get_contents($episode->getFirstMedia('hd_m3u8')->stream());
            foreach ($episode->getMedia('hls') as $track) {
                $content = str_replace($track->file_name, $track->getTemporaryUrl(Carbon::now()->addMinutes(intval(config('settings.s3_signed_time', 5)))), $content);
            }
        } else {
            $content = stream_get_contents($episode->getFirstMedia('m3u8')->stream());
            foreach ($episode->getMedia('hls') as $track) {
                $content = str_replace($track->file_name, $track->getFullUrl(), $content);
            }
        }

        return response($content)
            ->withHeaders([
                'Content-Type' => 'text/plain',
                'Cache-Control' => 'no-store, no-cache',
                'Content-Disposition' => 'attachment; filename="track.m3u8',
            ]);
    }
}