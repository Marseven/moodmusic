<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-07-05
 * Time: 18:01
 */

namespace App\Models;

use App\Jobs\ProcessHLS;
use App\Jobs\ProcessMp3;
use App\Jobs\ProcessPodcastHLS;
use App\Jobs\ProcessPodcastMp3;
use App\Jobs\ProcessPreview;
use App\Traits\SanitizedRequest;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Response;
use DB;
use Config;
use Image;
use Storage;
use Validator;
use File;
use FFMpeg;

class Upload
{
    use SanitizedRequest;

    public function handle($request, $artistIds = null, $album_id = null, $isAdminPanel = false)
    {
        /** validate file format */

        if(config('settings.ffmpeg') && @shell_exec(env('FFMPEG_PATH') . ' -version')) {
            if(config('settings.max_audio_file_size') == 0) {
                $request->validate([
                    'file.*' => 'required|mimetypes:application/octet-stream,audio/ogg,audio/x-wav,audio/wav,audio/mpeg,audio/flac,audio/x-hx-aac-adts,audio/x-m4a,video/mp4,video/x-ms-wma,audio/ac3,audio/aac',
                ]);
            } else {
                $request->validate([
                    'file.*' => 'required|mimetypes:application/octet-stream,audio/ogg,audio/x-wav,audio/wav,audio/mpeg,audio/flac,audio/x-hx-aac-adts,audio/x-m4a,video/mp4,video/x-ms-wma,audio/ac3,audio/aac|max:' . config('settings.max_audio_file_size', 51200),
                ]);
            }
        } else {
            if(config('settings.max_audio_file_size') == 0) {
                $request->validate([
                    'file.*' => 'required|mimetypes:audio/mpeg,application/octet-stream',
                ]);
            } else {
                $request->validate([
                    'file.*' => 'required|mimetypes:audio/mpeg,application/octet-stream|max:' . config('settings.max_audio_file_size', 10240),
                ]);
            }
        }

        if ($request->file('file')->isValid()) {
            $file_name_with_extension = $request->file('file')->getClientOriginalName();

            $getID3 = new \getID3;
            $mp3Info = $getID3->analyze($request->file('file')->getPathName());

            if(! isset($mp3Info['audio']) || ! isset($mp3Info['audio']['bitrate'])) {
                if(@shell_exec(env('FFMPEG_PATH') . ' -version')) {
                    $ffprobe = FFMpeg\FFProbe::create([
                        'ffmpeg.binaries' => env('FFMPEG_PATH', '/usr/bin/ffmpeg'),
                        'ffprobe.binaries' => env('FFPROBE_PATH', '/usr/bin/ffprobe'),
                        'timeout' => 3600,
                        'ffmpeg.threads' => 12,
                    ]);

                    $data = [
                        'bitRate' => intval($ffprobe->format($request->file('file')->getPathName())->get('duration')),
                        'playtimeSeconds' => intval($ffprobe->format($request->file('file')->getPathName())->get('bit_rate') / 1000),
                    ];

                    if(!isset($data['bitRate'])) {
                        header('HTTP/1.0 403 Forbidden');
                        header('Content-Type: application/json');
                        echo json_encode([
                            'message' => 'Not support',
                            'errors' => array('message' => array(__('Audio file is not supported')))
                        ]);
                        exit;
                    }
                } else {
                    $basicInfo = $this->getBasicInfo($request->file('file')->getPathName());
                    if(isset($basicInfo->bitrate) && isset($basicInfo->duration)) {
                        $data = [
                            'bitRate' => intval($basicInfo->bitrate),
                            'playtimeSeconds' => intval($basicInfo->duration)
                        ];
                    } else {
                        header('HTTP/1.0 403 Forbidden');
                        header('Content-Type: application/json');
                        echo json_encode([
                            'message' => 'Not support',
                            'errors' => array('message' => array(__('Audio file is not supported')))
                        ]);
                        exit;
                    }
                }
            } else {
                $data = [
                    'bitRate' => intval($mp3Info['audio']['bitrate'] / 1000),
                    'playtimeSeconds' => intval($mp3Info['playtime_seconds'])
                ];
            }

            Validator::make($data, [
                'bitRate' => ['required', 'numeric', 'min:' . config('settings.min_audio_bitrate', 64), 'max:' . config('settings.max_audio_bitrate', 320)],
                'playtimeSeconds' => ['required', 'numeric', 'min:' . config('settings.min_audio_duration', 60), 'max:' . config('settings.max_audio_duration', 300)],
            ])->validate();

            $trackInfo = array();

            if(isset($mp3Info['audio']['id3v2']['artist'][0])) {
                $trackInfo = $mp3Info['tags']['id3v2'];
            } elseif(isset($mp3Info['tags']['id3v2']['artist'][0])) {
                $trackInfo = $mp3Info['tags']['id3v2'];
            } elseif(isset($mp3Info['tags']['id3v1'])) {
                $trackInfo = $mp3Info['tags']['id3v1'];
            } elseif(isset($mp3Info['id3v2']['comments']['artist'][0])) {
                $trackInfo = $mp3Info['id3v2']['comments'];
            } elseif(isset($mp3Info['tags']['quicktime']['artist'][0])) {
                $trackInfo = $mp3Info['tags']['quicktime'];
            }

            $song = new Song();

            isset($trackInfo['title'][0]) ? $song['title'] = $trackInfo['title'][0] : $song['title'] = pathinfo($file_name_with_extension, PATHINFO_FILENAME);
            $song->duration = $data['playtimeSeconds'];

            if($artistIds)
            {
                $song->artistIds = $artistIds;
            } else {
                if($isAdminPanel)
                {
                    if (isset($trackInfo['artist'][0])) {
                        $artistName = $trackInfo['artist'][0];
                        $row = Artist::where('name', '=', $artistName)->first();
                        if (isset($row->id)) {
                            $song->artistIds = $row->id;
                            $song->genre = $row->genre;
                            $song->mood = $row->mood;
                        } else {
                            $artist = new Artist();
                            $artist->user_id = auth()->user()->id;
                            $artist->name = $artistName ? $artistName : 'Various Artists';
                            $artist->save();
                            $song->artistIds = $artist->id;
                        }
                    } else {
                        if($album_id) {
                            $album = Album::find($album_id);
                            $album->user_id = auth()->user()->id;
                            $song->artistIds = $album->artistIds;
                            $song->genre = $album->genre;
                            $song->mood = $album->mood;
                        } else {
                            $row = Artist::where('name', '=', 'Various Artists')->first();
                            if (isset($row->id)) {
                                $song->artistIds = $row->id;
                                $song->genre = $row->genre;
                                $song->mood = $row->mood;
                            } else {
                                $artist = new Artist();
                                $artist->user_id = auth()->user()->id;
                                $artist->name = 'Various Artists';
                                $artist->save();
                                $song->artistIds = $artist->id;
                            }
                        }
                    }
                }
            }

            if (isset($mp3Info['comments']['picture']['0']['data']) || isset($trackInfo['picture']['0']['data'])) {
                $pictureData = isset($mp3Info['comments']['picture']['0']['data']) ? $mp3Info['comments']['picture']['0']['data'] : $trackInfo['picture']['0']['data'];
                try {
                    $song->addMediaFromBase64(base64_encode(Image::make($pictureData)->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                        ->usingFileName(Str::random(10) . '.jpg')
                        ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
                } catch (\Exception $ex) {

                }
            }

            $song->save();

            $tempPath = Str::random(32);
            File::copy($request->file('file')->getPathName(), Storage::disk('public')->path($tempPath));
            $audio = new \stdClass();
            $audio->path = Storage::disk('public')->path($tempPath);
            $audio->original_name = $request->file('file')->getClientOriginalName();
            $audio->bitrate = $data['bitRate'];

            if(config('settings.ffmpeg') && @shell_exec(env('FFMPEG_PATH') . ' -version')) {
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
                $song->addMedia($request->file('file')->getPathName())
                    ->usingFileName(Str::random(10) . '.mp3', PATHINFO_FILENAME)
                    ->withCustomProperties(['bitrate' => $data['bitRate']])
                    ->toMediaCollection('audio', config('settings.storage_audio_location', 'public'));
                $song->mp3 = 1;
            }

            $song->user_id = auth()->user()->id;

            if($isAdminPanel)
            {
                $song->approved = 1;
            }

            $song->save();

            if($album_id) {
                DB::table('album_songs')->insert([
                    'song_id' => $song->id,
                    'album_id' => $album_id,
                    'priority' => (intval(Carbon::parse($_SERVER['REQUEST_TIME_FLOAT'])->format('disu')) / 1000)
                ]);

                $album = Album::find($album_id);
                $song->artistIds = $album->artistIds;
                $song->genre = $album->genre;
                $song->mood = $album->mood;
                $song->save();
            } elseif($isAdminPanel) {
                if (isset($trackInfo['album'][0])) {
                    $row = Album::where('title', '=', $trackInfo['album'][0])->first();

                    if (isset($row->id)) {
                        DB::table('album_songs')->insert(
                            [ 'song_id' => $song->id, 'album_id' => $row->id ]
                        );
                    } else {
                        $album = new Album();
                        $album->title = $trackInfo['album'][0];
                        $album->artistIds = $song['artistIds'];
                        $album->approved = 1;

                        if (isset($mp3Info['comments']['picture']['0']['data']) || isset($trackInfo['picture']['0']['data'])) {
                            $pictureData = isset($mp3Info['comments']['picture']['0']['data']) ? $mp3Info['comments']['picture']['0']['data'] : $trackInfo['picture']['0']['data'];
                            $album->addMediaFromBase64(base64_encode(Image::make($pictureData)->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                                ->usingFileName(Str::random(10) . '.jpg')
                                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
                        }

                        $album->save();
                        DB::table('album_songs')->insert(
                            [ 'song_id' => $song->id, 'album_id' => $album->id ]
                        );
                    }
                }
            }

            return Song::withoutGlobalScopes()->findOrFail($song->id);
        }
    }

    public function handleEpisode($request, $podcast_id)
    {
        if(config('settings.podcast_ffmpeg') && @shell_exec(env('FFMPEG_PATH') . ' -version')) {
            if(intval(config('settings.podcast_max_audio_file_size')) == 0) {
                $request->validate([
                    'file.*' => 'required|mimetypes:application/octet-stream,audio/ogg,audio/x-wav,audio/wav,audio/mpeg,audio/flac,audio/x-hx-aac-adts,audio/x-m4a,video/mp4,video/x-ms-wma,audio/ac3,audio/aac',
                ]);
            } else {
                $request->validate([
                    'file.*' => 'required|mimetypes:application/octet-stream,audio/ogg,audio/x-wav,audio/wav,audio/mpeg,audio/flac,audio/x-hx-aac-adts,audio/x-m4a,video/mp4,video/x-ms-wma,audio/ac3,audio/aac|max:' . intval(config('settings.podcast_max_audio_file_size', 51200)),
                ]);
            }
        } else {
            $request->validate([
                'file.*' => 'required|mimetypes:audio/mpeg,application/octet-stream|max:' . config('settings.podcast_max_audio_file_size', 51200),
            ]);
        }

        if ($request->file('file')->isValid()) {
            $file_name_with_extension = $request->file('file')->getClientOriginalName();

            $getID3 = new \getID3;
            $mp3Info = $getID3->analyze($request->file('file')->getPathName());

            if(! isset($mp3Info['audio']) || ! isset($mp3Info['audio']['bitrate'])) {
                if(@shell_exec(env('FFMPEG_PATH') . ' -version')) {
                    $ffprobe = FFMpeg\FFProbe::create([
                        'ffmpeg.binaries' => env('FFMPEG_PATH', '/usr/bin/ffmpeg'),
                        'ffprobe.binaries' => env('FFPROBE_PATH', '/usr/bin/ffprobe'),
                        'timeout' => 3600,
                        'ffmpeg.threads' => 12,
                    ]);

                    $data = [
                        'bitRate' => intval($ffprobe->format($request->file('file')->getPathName())->get('duration')),
                        'playtimeSeconds' => intval($ffprobe->format($request->file('file')->getPathName())->get('bit_rate') / 1000),
                    ];

                    if(!isset($data['bitRate'])) {
                        header('HTTP/1.0 403 Forbidden');
                        header('Content-Type: application/json');
                        echo json_encode([
                            'message' => 'Not support',
                            'errors' => array('message' => array(__('Audio file is not supported')))
                        ]);
                        exit;
                    }
                } else {
                    $basicInfo = $this->getBasicInfo($request->file('file')->getPathName());
                    if(isset($basicInfo->bitrate) && isset($basicInfo->duration)) {
                        $data = [
                            'bitRate' => intval($basicInfo->bitrate),
                            'playtimeSeconds' => intval($basicInfo->duration)
                        ];
                    } else {
                        header('HTTP/1.0 403 Forbidden');
                        header('Content-Type: application/json');
                        echo json_encode([
                            'message' => 'Not support',
                            'errors' => array('message' => array(__('Audio file is not supported')))
                        ]);
                        exit;
                    }
                }
            } else {
                $data = [
                    'bitRate' => intval($mp3Info['audio']['bitrate'] / 1000),
                    'playtimeSeconds' => intval($mp3Info['playtime_seconds'])
                ];
            }

            Validator::make($data, [
                'bitRate' => ['required', 'numeric', 'min:' . config('settings.podcast_min_audio_bitrate', 64), 'max:' . config('settings.podcast_max_audio_bitrate', 320)],
                'playtimeSeconds' => ['required', 'numeric', 'min:' . config('settings.podcast_min_audio_duration', 60), 'max:' . config('settings.podcast_max_audio_duration', 300)],
            ])->validate();

            $trackInfo = array();

            if(isset($mp3Info['audio']['id3v2']['title'][0])) {
                $trackInfo = $mp3Info['tags']['id3v2'];
            }elseif(isset($mp3Info['tags']['id3v2']['title'][0])) {
                $trackInfo = $mp3Info['tags']['id3v2'];
            } elseif(isset($mp3Info['tags']['id3v1'])) {
                $trackInfo = $mp3Info['tags']['id3v1'];
            } elseif(isset($mp3Info['id3v2']['comments']['title'][0])) {
                $trackInfo = $mp3Info['id3v2']['comments'];
            }

            $episode = new Episode();

            isset($trackInfo['title'][0]) ? $episode['title'] = $trackInfo['title'][0] : $episode['title'] = pathinfo($file_name_with_extension, PATHINFO_FILENAME);
            $episode->duration = intval($mp3Info['playtime_seconds']);
            $episode->podcast_id = $podcast_id;
            $episode->save();

            $tempPath = Str::random(32);
            File::copy($request->file('file')->getPathName(), Storage::disk('public')->path($tempPath));

            $audio = new \stdClass();
            $audio->path = Storage::disk('public')->path($tempPath);
            $audio->original_name = $request->file('file')->getClientOriginalName();

            if(config('settings.podcast_ffmpeg') && @shell_exec(env('FFMPEG_PATH') . ' -version')) {
                $episode->pending = 1;
                $episode->save();

                if(! config('settings.podcast_audio_stream_hls')) {
                    dispatch(new ProcessPodcastMp3($episode, $audio));
                } else if(config('settings.podcast_audio_stream_hls')) {
                    dispatch(new ProcessPodcastHLS($episode, $audio));
                }
            } else {
                $episode->addMedia($request->file('file')->getPathName())
                    ->usingFileName(Str::random(10) . '.mp3', PATHINFO_FILENAME)
                    ->withCustomProperties(['bitrate' => intval($mp3Info['audio']['bitrate'] / 1000)])
                    ->toMediaCollection('audio', config('settings.storage_audio_location', 'public'));
                $episode->mp3 = 1;
            }
            $episode->user_id = auth()->user()->id;
            $episode->save();

            return $episode;
        }
    }

    public function getBasicInfo($filename)
    {
        $fd = fopen($filename, "rb");

        $buffer = new \stdClass();
        $duration=0;
        $block = fread($fd, 100);
        $offset = $this->skipID3v2Tag($block);
        fseek($fd, $offset, SEEK_SET);
        while (!feof($fd))
        {
            $block = fread($fd, 10);
            if (strlen($block)<10) { break; }
            else if ($block[0]=="\xff" && (ord($block[1])&0xe0) )
            {
                $info = self::parseFrameHeader(substr($block, 0, 4));
                if (empty($info['Framesize'])) {
                    $buffer->duration = $duration;
                    $buffer->bitrate = $info['Bitrate'];
                    return $buffer;
                }
                fseek($fd, $info['Framesize']-10, SEEK_CUR);
                $duration += ( $info['Samples'] / $info['Sampling Rate'] );
                $buffer->bitrate = $info['Bitrate'];
            }
            else if (substr($block, 0, 3)=='TAG')
            {
                fseek($fd, 128-10, SEEK_CUR);
            }
            else
            {
                fseek($fd, -9, SEEK_CUR);
            }
        }
        $buffer->duration = round($duration);
        return $buffer;
    }

    private function skipID3v2Tag(&$block)
    {
        if (substr($block, 0,3) =="ID3")
        {
            $id3v2_major_version = ord($block[3]);
            $id3v2_minor_version = ord($block[4]);
            $id3v2_flags = ord($block[5]);
            $flag_unsynchronisation  = $id3v2_flags & 0x80 ? 1 : 0;
            $flag_extended_header    = $id3v2_flags & 0x40 ? 1 : 0;
            $flag_experimental_ind   = $id3v2_flags & 0x20 ? 1 : 0;
            $flag_footer_present     = $id3v2_flags & 0x10 ? 1 : 0;
            $z0 = ord($block[6]);
            $z1 = ord($block[7]);
            $z2 = ord($block[8]);
            $z3 = ord($block[9]);
            if ( (($z0&0x80)==0) && (($z1&0x80)==0) && (($z2&0x80)==0) && (($z3&0x80)==0) )
            {
                $header_size = 10;
                $tag_size = (($z0&0x7f) * 2097152) + (($z1&0x7f) * 16384) + (($z2&0x7f) * 128) + ($z3&0x7f);
                $footer_size = $flag_footer_present ? 10 : 0;
                return $header_size + $tag_size + $footer_size;//bytes to skip
            }
        }
        return 0;
    }

    public static function parseFrameHeader($fourbytes)
    {
        static $versions = array(
            0x0=>'2.5',0x1=>'x',0x2=>'2',0x3=>'1', // x=>'reserved'
        );
        static $layers = array(
            0x0=>'x',0x1=>'3',0x2=>'2',0x3=>'1', // x=>'reserved'
        );
        static $bitrates = array(
            'V1L1'=>array(0,32,64,96,128,160,192,224,256,288,320,352,384,416,448),
            'V1L2'=>array(0,32,48,56, 64, 80, 96,112,128,160,192,224,256,320,384),
            'V1L3'=>array(0,32,40,48, 56, 64, 80, 96,112,128,160,192,224,256,320),
            'V2L1'=>array(0,32,48,56, 64, 80, 96,112,128,144,160,176,192,224,256),
            'V2L2'=>array(0, 8,16,24, 32, 40, 48, 56, 64, 80, 96,112,128,144,160),
            'V2L3'=>array(0, 8,16,24, 32, 40, 48, 56, 64, 80, 96,112,128,144,160),
        );
        static $sample_rates = array(
            '1'   => array(44100,48000,32000),
            '2'   => array(22050,24000,16000),
            '2.5' => array(11025,12000, 8000),
        );
        static $samples = array(
            1 => array( 1 => 384, 2 =>1152, 3 =>1152, ),
            2 => array( 1 => 384, 2 =>1152, 3 => 576, ),
        );

        $b1=ord($fourbytes[1]);
        $b2=ord($fourbytes[2]);
        $b3=ord($fourbytes[3]);

        $version_bits = ($b1 & 0x18) >> 3;
        $version = $versions[$version_bits];
        $simple_version =  ($version=='2.5' ? 2 : $version);

        $layer_bits = ($b1 & 0x06) >> 1;
        $layer = $layers[$layer_bits];

        $protection_bit = ($b1 & 0x01);
        $bitrate_key = sprintf('V%dL%d', $simple_version , $layer);
        $bitrate_idx = ($b2 & 0xf0) >> 4;
        $bitrate = isset($bitrates[$bitrate_key][$bitrate_idx]) ? $bitrates[$bitrate_key][$bitrate_idx] : 0;

        $sample_rate_idx = ($b2 & 0x0c) >> 2;//0xc => b1100
        $sample_rate = isset($sample_rates[$version][$sample_rate_idx]) ? $sample_rates[$version][$sample_rate_idx] : 0;
        $padding_bit = ($b2 & 0x02) >> 1;
        $private_bit = ($b2 & 0x01);
        $channel_mode_bits = ($b3 & 0xc0) >> 6;
        $mode_extension_bits = ($b3 & 0x30) >> 4;
        $copyright_bit = ($b3 & 0x08) >> 3;
        $original_bit = ($b3 & 0x04) >> 2;
        $emphasis = ($b3 & 0x03);

        $info = array();
        $info['Version'] = $version;//MPEGVersion
        $info['Layer'] = $layer;
        $info['Bitrate'] = $bitrate;
        $info['Sampling Rate'] = $sample_rate;
        $info['Framesize'] = self::frameSize($layer, $bitrate, $sample_rate, $padding_bit);
        $info['Samples'] = $samples[$simple_version][$layer];
        return $info;
    }

    private static function frameSize($layer, $bitrate,$sample_rate,$padding_bit)
    {
        if ($layer == 1)
            return intval(((12 * $bitrate*1000 /$sample_rate) + $padding_bit) * 4);
        else
            return intval(((144 * $bitrate*1000)/$sample_rate) + $padding_bit);
    }
}