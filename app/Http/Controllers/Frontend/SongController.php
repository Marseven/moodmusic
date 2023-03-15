<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-28
 * Time: 15:44
 */

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\Role;
use App\Models\Download;
use App\Models\User;
use Illuminate\Support\Str;
use View;
use Storage;
use FFMpeg;

class SongController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        if($this->request->is('api*') || $this->request->wantsJson())
        {
            $song = Song::withoutGlobalScopes()->findOrFail($this->request->route('id'));

            if($this->request->get('callback'))
            {
                return response()->jsonp($this->request->get('callback'), [$song])->header('Content-Type', 'application/javascript');
            }

            return response()->json($song->append(['genres', 'moods']));
        }

        $song = Song::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        if(! $song->approved && auth()->check() && Role::getValue('admin_songs')) {

        } else {
            if(! isset($song->id)) {
                abort(404);
            } elseif(auth()->check() && ! $song->visibility && ($song->user_id != auth()->user()->id)) {
                abort(404);
            }  elseif(! auth()->check() && ! $song->visibility) {
                abort(404);
            } elseif(! $song->approved) {
                abort(404);
            }
        }

        $view = View::make('song.index')
            ->with('song', $song);

        if(count($song->artists) == 1) {
            $artist = $song->artists->first();
            $artist->setRelation('songs', $artist->songs()->where('id', '!=', $song->id)->paginate(5));
            $view = $view->with('related', $artist);
        }

        if ($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags($song);

        return $view;
    }

    public function download()
    {
        $song = Song::withoutGlobalScopes()->findOrFail($this->request->route('id'));
        if($song->mp3 && $song->allow_download) {
            if($this->request->route()->getName() == 'frontend.song.download') {
                if(!! Role::getValue('option_download')) {
                    $file = new Download (
                        $song->getFirstMedia('audio'),
                        $song->title . '.mp3',
                        intval(Role::getValue('option_download_resume')),
                        intval(Role::getValue('option_download_speed'))
                    );

                    $song->increment('download_count');
                    session_write_close();
                    $file->downloadFile();
                    die();
                } else {
                    abort(403);
                }
            } else if($this->request->route()->getName() == 'frontend.song.download.hd') {
                if(!! Role::getValue('option_download_hd')) {
                    $file = new Download (
                        $song->getFirstMedia('hd_audio'),
                        $song->title . '.mp3',
                        intval(Role::getValue('option_download_resume')),
                        intval(Role::getValue('option_download_speed'))
                    );

                    $song->increment('download_count');
                    session_write_close();
                    $file->downloadFile();
                    die();
                } else {
                    abort(403);
                }
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    public function songFromIds()
    {
        $ids = $this->request->route('ids');

        $songs = Song::whereIn('id', explode(',', $this->request->route('ids')))->get();


        if( $this->request->is('api*') )
        {
            if($this->request->get('callback'))
            {
                return response()->jsonp($this->request->get('callback'), $songs)->header('Content-Type', 'application/javascript');
            }

            return response()->json($songs);
        }
    }

    public function autoplay()
    {
        $this->request->validate([
            'type' => 'required|string|in:activity,song,artist,album,playlist,queue,user,genre,mood,recent,community,obsessed,trending',
            'id' => 'nullable|integer',
            'recent_songs' => 'nullable|string'
        ]);

        $song = new Song;

        switch ($this->request->input('type')) {
            case 'song':
                break;
            case 'artist':
                $song = $song->where('artistIds', 'REGEXP', '(^|,)(' . $this->request->input('id') . ')(,|$)');
                break;
            case 'album':
                $song = $song->leftJoin('album_songs', 'album_songs.song_id', '=', 'songs.id')
                    ->select('songs.*', 'album_songs.id as host_id');
                $song = $song->where(function ($query) {
                    $query->where('album_songs.album_id', '=', $this->request->input('id'));
                });
                break;
            case 'playlist':
                $song = $song->leftJoin('playlist_songs', 'playlist_songs.song_id', '=', 'songs.id')
                    ->select('songs.*', 'playlist_songs.id as host_id');
                $song = $song->where(function ($query) {
                    $query->where('playlist_songs.playlist_id', '=', $this->request->input('id'));
                });
                break;
            case 'queue':
                break;
            case 'user':
                $user = User::find($this->request->input('id'));
                $song = $user->recent();
                break;
            case 'genre':
                $song = $song->where('genre', 'REGEXP', '(^|,)(' . implode(',', $this->request->input('id')) . ')(,|$)');
                break;
            case 'mood':
                $song = $song->where('mood', 'REGEXP', '(^|,)(' . implode(',', $this->request->input('id')) . ')(,|$)');
                break;
            case 'recent':
                $user = User::find($this->request->input('id'));
                $song = $user->recent();
                break;
            case 'community':
                $user = User::find($this->request->input('id'));
                $song = $user->communitySongs();
                break;
            case 'obsessed':
                $user = User::find($this->request->input('id'));
                $song = $user->obsessed();
                break;
            default:
                $song = new Song;
                break;
        }


        if($this->request->input('recent_songs')) {
            $song = $song->whereNotIn('songs.id', explode(',', $this->request->input('recent_songs')));
        }

        $song = $song->inRandomOrder()->first();

        return response()->json($song);
    }

    public function downloadFromYT()
    {
        $song = Song::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        if(isset($song->log) && isset($song->log->youtube)) {
            $youtube_id = $song->log->youtube;
        } else {
            $data = app('App\Http\Controllers\Frontend\StreamController')->youtube($song->id, false);
            $videos = $data->getData()->items;
            $youtube_id = $videos[0]->id->videoId;
        }

        if(@shell_exec(env('FFMPEG_PATH') . ' -version') && @shell_exec(config('settings.youtube_dl_path', '/usr/local/bin/youtube-dl') . ' --version')) {
            $tempPath = Str::random(32);
            $output =  shell_exec(config('settings.youtube_dl_path', '/usr/local/bin/youtube-dl') . ' -o "' . Storage::disk('public')->path($tempPath) . '.m4a" -f m4a https://www.youtube.com/watch?v=' . $youtube_id);

            $ffmpeg = FFMpeg\FFMpeg::create([
                'ffmpeg.binaries' => env('FFMPEG_PATH', '/usr/bin/ffmpeg'),
                'ffprobe.binaries' => env('FFPROBE_PATH', '/usr/bin/ffprobe'),
                'timeout' => 3600,
                'ffmpeg.threads' => 12,
            ]);

            $audio = $ffmpeg->open( Storage::disk('public')->path($tempPath) . '.m4a');
            $audio->save(new FFMpeg\Format\Audio\Mp3(), Storage::disk('public')->path($tempPath) . '.mp3');

            @unlink(Storage::disk('public')->path($tempPath) . '.m4a');

            header("Content-disposition: attachment; filename=" . $song->title . ".mp3");
            header("Content-type: audio/mpeg");

            ignore_user_abort(true);
            if (connection_aborted()) {
                @unlink(Storage::disk('public')->path($tempPath) . '.mp3');
            }

            readfile(Storage::disk('public')->path($tempPath) . '.mp3');
        } else {
            $info = new \App\Modules\YoutubeDownloader\YouTubeDownloader();
            $links = collect($info->getDownloadLinks($youtube_id))->first();
            $m4a = collect($links)->firstWhere('itag', '140');

            if(is_object($m4a)) {
                header("Content-disposition: attachment; filename=" . $song->title . ".m4a");
                header("Content-type: audio/m4a");
                readfile($m4a->url);
            } else {
                abort(403, 'Can not download the audio.');
            }
        }
    }

    public function downloadOffline()
    {
        $song = Song::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        if($song->mp3) {
            if($this->request->route()->getName() == 'api.song.offline.download') {
                if($song->getFirstMedia('audio')->disk == 's3') {
                    header("Location: " . $song->getFirstTemporaryUrl(Carbon::now()->addMinutes(intval(config('settings.s3_signed_time', 5))), 'audio'));
                    exit;
                } else {
                    header("Location: " . $song->getFirstMedia('audio')->getUrl());
                    exit;
                }
            } else if($this->request->route()->getName() == 'api.song.offline.download.hd') {
                if($song->getFirstMedia('audio')->disk == 's3') {
                    header("Location: " . $song->getFirstTemporaryUrl(Carbon::now()->addMinutes(intval(config('settings.s3_signed_time', 5))), 'hd_audio'));
                    exit;
                } else {
                    header("Location: " . $song->getFirstMedia('hd_audio')->getUrl());
                    exit;
                }
            }
        } else {
            abort(404);
        }
    }
}
