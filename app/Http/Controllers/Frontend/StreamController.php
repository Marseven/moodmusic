<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-18
 * Time: 21:20
 */

namespace App\Http\Controllers\Frontend;

use App\Models\Episode;
use App\Models\Popular;
use App\Models\Role;
use App\Models\Song;
use App\Models\Artist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\History;
use Auth;
use File;
use DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Stream;

class StreamController
{
    private $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function mp3(){
        $song = Song::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        if($song->getFirstMedia('audio')->disk == 's3') {
            header("Location: " . $song->getFirstTemporaryUrl(Carbon::now()->addMinutes(intval(config('settings.s3_signed_time', 5))), 'audio'));
            exit();
        } else {
            if(config('settings.direct_stream')) {
                header("Location: " . $song->getFirstMedia('audio')->getUrl());
                exit();
            } else {
                header('Content-type: ' . $song->getFirstMedia('audio')->mime_type);
                header('Content-Length: ' . $song->getFirstMedia('audio')->size);
                header('Content-Disposition: attachment; filename="' . $song->getFirstMedia('audio')->file_name);
                header('Cache-Control: no-cache');
                header('Accept-Ranges: bytes');

                if(config('filesystems.disks')[$song->getFirstMedia('audio')->disk]['driver'] == 'local') {
                    readfile($song->getFirstMedia('audio')->getPath());
                } else {
                    readfile($song->getFirstMedia('audio')->getUrl());
                }
                exit();
            }
        }
    }

    public function hdMp3(){
        $song = Song::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        if($song->getFirstMedia('hd_audio')->disk == 's3') {
            header("Location: " . $song->getFirstTemporaryUrl(Carbon::now()->addMinutes(intval(config('settings.s3_signed_time', 5))), 'audio_hd'));
            exit();
        } else {
            if(config('settings.direct_stream')) {
                header("Location: " . $song->getFirstMedia('hd_audio')->getUrl());
                exit();
            } else {
                header('Content-type: ' . $song->getFirstMedia('hd_audio')->mime_type);
                header('Content-Length: ' . $song->getFirstMedia('hd_audio')->size);
                header('Content-Disposition: attachment; filename="' . $song->getFirstMedia('hd_audio')->file_name);
                header('Cache-Control: no-cache');
                header('Accept-Ranges: bytes');

                if(config('filesystems.disks')[$song->getFirstMedia('hd_audio')->disk]['driver'] == 'local') {
                    readfile($song->getFirstMedia('hd_audio')->getPath());
                } else {
                    readfile($song->getFirstMedia('hd_audio')->getUrl());
                }
                exit();
            }
        }
    }

    public function hls(){
        $song = Song::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        if($song->getFirstMedia('hls')->disk == 's3') {
            $content = file_get_contents($song->getFirstTemporaryUrl(Carbon::now()->addMinutes(intval(config('settings.s3_signed_time', 5))), 'm3u8'));
            foreach ($song->getMedia('hls') as $track) {
                $content = str_replace($track->file_name, $track->getTemporaryUrl(Carbon::now()->addMinutes(intval(config('settings.s3_signed_time', 5)))), $content);
            }
        } else {
            $content = stream_get_contents($song->getFirstMedia('m3u8')->stream());
            foreach ($song->getMedia('hls') as $track) {
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

    public function hlsHD(){
        $song = Song::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        if($song->getFirstMedia('hd_hls')->disk == 's3') {
            $content = file_get_contents($song->getFirstTemporaryUrl(Carbon::now()->addMinutes(intval(config('settings.s3_signed_time', 5))), 'hd_m3u8'));
            foreach ($song->getMedia('hd_hls') as $track) {
                $content = str_replace($track->file_name, $track->getTemporaryUrl(Carbon::now()->addMinutes(intval(config('settings.s3_signed_time', 5)))), $content);
            }
        } else {
            $content = stream_get_contents($song->getFirstMedia('hd_m3u8')->stream());
            foreach ($song->getMedia('hd_hls') as $track) {
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

    public function onTrackPlayed(){
        //Increase song plays time
        $this->request->validate([
            'type' => 'string|in:song,episode',
        ]);

        if($this->request->input('type') == 'song') {
            $song = Song::findOrFail($this->request->input('id'));
            $song->increment('plays');
            if(isset($song->artists[0])) {
                $artist_id = $song->artists[0]->id;

                if(isset($song->genre)) {
                    $genre = $song->genre;
                } else {
                    $genre = $song->artists[0]->genre;
                }

                if(isset($song->mood)) {
                    $mood = $song->mood;
                } else {
                    $mood = $song->artists[0]->mood;
                }

                Popular::updateOrCreate(
                    [
                        'song_id' => $song->id,
                        'created_at' => Carbon::now()->format('Y-m-d'),
                    ],
                    [
                        'artist_id' => $artist_id,
                        'album_id' => isset($song->album) ? $song->album->id : null,
                        'genre' => $genre,
                        'mood' => $mood,
                        'plays' => DB::raw('plays + 1')
                    ]
                );
            }

            if ($song->user_id && config('settings.monetization') && Role::getUserValue('monetization_streaming', $song->user_id)) {

                if ($song->user_id && config('settings.monetization') && Role::getUserValue('monetization_streaming', $song->user_id)) {
                    if(request()->ip()) {
                        if(! Stream::where('streamable_id', $song->id)->where('streamable_type', (new Song)->getMorphClass())->where('ip', request()->ip())->exists()) {
                            $revenue = Role::getUserValue('monetization_streaming_rate', $song->user_id);
                            $steam = new Stream();
                            $steam->user_id = $song->user_id;
                            $steam->streamable_id = $song->id;
                            $steam->streamable_type = (new Song)->getMorphClass();
                            $steam->revenue = $revenue;
                            $steam->ip = request()->ip();
                            $steam->save();
                            $song->user()->increment('balance', $revenue);
                            foreach ($song->artists as $artist) {
                                $artist->increment('impression', $revenue);
                            }
                            $song->increment('stream_impression', $revenue);
                        }
                    }
                }

            }

            if(auth()->check())
            {
                makeActivity(auth()->user()->id, $song->id, (new Song)->getMorphClass(), 'playSong', $song->id);
                History::updateOrCreate(
                    [
                        'user_id' => auth()->user()->id,
                        'historyable_id' => $song->id,
                        'historyable_type' => (new Song)->getMorphClass(),
                    ],
                    [
                        'created_at' => Carbon::now(),
                        'ownerable_type' => (new Artist)->getMorphClass(),
                        'ownerable_id' => isset($song->artists[0]) ? $song->artists[0]->id : null,
                        'interaction_count' => DB::raw('interaction_count + 1')
                    ]
                );
            }

            return response()->json(array('success' => true));

        } else {
            $episode = Episode::findOrFail($this->request->input('id'));
            $episode->increment('plays');

            Popular::updateOrCreate(
                [
                    'episode_id' => intval($episode->id),
                    'created_at' => Carbon::now()->format('Y-m-d'),
                ],
                [
                    'artist_id' => $episode->podcast->artist_id,
                    'genre' => $episode->podcast->category,
                    'plays' => DB::raw('plays + 1')
                ]
            );

            Popular::updateOrCreate(
                [
                    'podcast_id' => intval($episode->podcast->id),
                    'created_at' => Carbon::now()->format('Y-m-d'),
                ],
                [
                    'artist_id' => $episode->podcast->artist_id,
                    'genre' => $episode->podcast->category,
                    'plays' => DB::raw('plays + 1')
                ]
            );

            if ($episode->user_id && config('settings.monetization') && Role::getUserValue('monetization_streaming', $episode->user_id)) {
                if(request()->ip()) {
                    if(! Stream::where('streamable_id', $episode->id)->where('streamable_type', (new Episode)->getMorphClass())->where('ip', request()->ip())->exists()) {
                        $revenue = Role::getUserValue('monetization_streaming_rate', $episode->user_id);
                        $steam = new Stream();
                        $steam->user_id = $episode->user_id;
                        $steam->streamable_id = $episode->id;
                        $steam->streamable_type = (new Episode)->getMorphClass();
                        $steam->revenue = $revenue;
                        $steam->ip = request()->ip();
                        $steam->save();
                        $episode->user()->increment('balance', $revenue);
                    }
                }
            }

            return response()->json(array('success' => true));
        }
    }

    public function youtube($id, $lyrics = false){
        $song = Song::withoutGlobalScopes()->findOrFail($id);
        $videos = [];

        if(isset($song->log) && isset($song->log->youtube)) {
            $videos[] = [
                'title' => $song->title,
                'id' => [
                    'videoId' => $song->log->youtube
                ]
            ];

            $buffer = array();
            $buffer['items'] = $videos;

            if($this->request->get('callback'))
            {
                return response()->jsonp($this->request->get('callback'), $buffer)->header('Content-Type', 'application/javascript');
            }

            return response()->json($buffer);
        }

        $query = urlencode($song->title . ' ' . $song->artists[0]->name . ($lyrics ? ' lyrics': ''));

        $response = Http::get("https://www.youtube.com/results?search_query={$query}");
        $html = $response->body();

        if (Str::contains($html, 'https://consent.youtube.com/s') && ! Str::contains($html, 'ytInitialData')) {

            $document = new \DOMDocument();
            @$document->loadHTML($html);

            $inputs = $document->getElementsByTagName("input");

            $formInputs = array();

            foreach ($inputs as $input) {
                if($input->getAttribute("name")) {
                    $formInputs[$input->getAttribute("name")] = $input->getAttribute("value");
                }
            }

            $response = Http::asForm()->post('https://consent.youtube.com/s', [
                'gl' => $formInputs['gl'],
                'm' => $formInputs['m'],
                'pc' => $formInputs['pc'],
                'continue' => $formInputs['continue'],
                'ca' => $formInputs['ca'],
                'x' => $formInputs['x'],
                'v' => $formInputs['v'],
                't' => $formInputs['t'],
                'hl' => $formInputs['hl'],
                'src' => $formInputs['src'],
                'uxe' => $formInputs['uxe']
            ]);

            $html = $response->body();
        }

        if (Str::contains($html, 'ytInitialData')) {
            $first_step = explode('ytInitialData', $html);
            if(isset($first_step[1])) {
                $second_step = explode('</script>', $first_step[1]);
            }
            if(isset($second_step[0])) {
                $json = substr($second_step[0], 2, -1);;
                $json = json_decode($json, true);
                $videos = $json['contents']['twoColumnSearchResultsRenderer']['primaryContents']['sectionListRenderer']['contents'][0]['itemSectionRenderer']['contents'];
                $videos = array_filter($videos, function ($video) {
                    return isset($video['videoRenderer']);
                });

                $videos = array_slice($videos, 0, 5);
                $videos = array_map(function($video) use($json) {
                    $video = $video['videoRenderer'];
                    return [
                        'title' => $video['title']['runs'][0]['text'],
                        'id' => [
                            'videoId' => $video['videoId']
                        ]
                    ];
                }, $videos);

            } else {
                abort('500', 'Can not get youtube video id');
            }
        } else {
            abort('500', 'Can not get youtube site content');
        }

        $buffer = array();
        $buffer['items'] = $videos;

        if(count($videos) && isset($song->log)) {
            $song->log->youtube = $videos[0]['id']['videoId'];
            $song->log->save();
        }

        if($this->request->get('callback'))
        {

            return response()->jsonp($this->request->get('callback'), $buffer)->header('Content-Type', 'application/javascript');
        }

        return response()->json($buffer);
    }

    public function saveWaveform(){
        $this->request->validate([
            'perk' => 'string',
        ]);

        $song = Song::findOrFail($this->request->route('id'));
        $song->clearMediaCollection('peaks');
        $song->addMediaFromString($this->request->input('perk'))->usingFileName('peaks.txt')
            ->toMediaCollection('peaks', config('settings.storage_artwork_location', 'public'));
        $song->waveform = 1;
        $song->save();

        return response()->json(['success' => true]);
    }

    public function getWaveform()
    {
        $song = Song::findOrFail($this->request->route('id'));

        if($song->getFirstMedia('peaks')->disk == 's3') {
            header("Location: " . $song->getFirstTemporaryUrl(Carbon::now()->addMinutes(intval(config('settings.s3_signed_time', 5))), 'audio'));
            exit();
        } else {
            if(config('filesystems.disks')[$song->getFirstMedia('peaks')->disk]['driver'] == 'local') {
                echo file_get_contents($song->getFirstMedia('peaks')->getPath());
            } else {
                echo file_get_contents($song->getFirstMedia('peaks')->getUrl());
            }
            exit();
        }
    }
}