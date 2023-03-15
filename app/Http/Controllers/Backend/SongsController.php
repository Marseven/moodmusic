<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-24
 * Time: 20:12
 */

namespace App\Http\Controllers\Backend;

use App\Jobs\ProcessHLS;
use App\Jobs\ProcessMp3;
use App\Jobs\ProcessPreview;
use App\Models\Email;
use App\Models\Lyric;
use App\Models\SongLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use View;
use App\Models\Song;
use App\Models\Album;
use Storage;
use Image;
use Validator;
use FFMpeg;
use File;

class SongsController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(Request $request)
    {
        $songs = Song::withoutGlobalScopes();

        if ($this->request->has('term'))
        {
            $songs = $songs->where('title', 'like', '%' . $this->request->input('term') . '%');
        }

        if ($this->request->input('artistIds') && is_array($this->request->input('artistIds')))
        {
            $songs = $songs->where(function ($query) {
                foreach($this->request->input('artistIds') as $index => $artistId) {
                    if($index == 0) {
                        $query->where('artistIds', 'REGEXP', '(^|,)(' . $artistId . ')(,|$)');
                    } else {
                        $query->orWhere('artistIds', 'REGEXP', '(^|,)(' . $artistId . ')(,|$)');
                    }
                }
            });
        }

        if ($this->request->input('userIds') && is_array($this->request->input('userIds')))
        {
            $songs = $songs->where(function ($query) {
                foreach($this->request->input('userIds') as $index => $userId) {
                    if($index == 0) {
                        $query->where('user_id', '=', $userId);
                    } else {
                        $query->orWhere('user_id', '=', $userId);
                    }
                }
            });
        }

        if ($this->request->input('genre') && is_array($this->request->input('genre')))
        {
            $songs = $songs->where('genre', 'REGEXP', '(^|,)(' . implode(',', $this->request->input('genre')) . ')(,|$)');
        }

        if ($this->request->input('mood') && is_array($this->request->input('mood')))
        {
            $songs = $songs->where('mood', 'REGEXP', '(^|,)(' . implode(',', $this->request->input('mood')) . ')(,|$)');
        }

        if ($this->request->input('created_from'))
        {
            $songs = $songs->where('created_at', '>=', Carbon::parse($this->request->input('created_from')));
        }

        if ($this->request->has('created_until'))
        {
            $songs = $songs->where('created_at', '<=', Carbon::parse($this->request->input('created_until')));
        }

        if ($this->request->input('comment_count_from'))
        {
            $songs = $songs->where('comment_count', '>=', intval($this->request->input('comment_count_from')));
        }

        if ($this->request->has('comment_count_until'))
        {
            $songs = $songs->where('comment_count', '<=', intval($this->request->input('comment_count_until')));
        }

        if ($this->request->input('duration_from'))
        {
            $songs = $songs->where('duration', '>=', intval($this->request->input('duration_from')));
        }

        if ($this->request->has('duration_until'))
        {
            $songs = $songs->where('duration', '<=', intval($this->request->input('duration_until')));
        }

        if ($this->request->has('comment_disabled'))
        {
            $songs = $songs->where('allow_comments', '=', 0);
        }

        if ($this->request->has('not_approved'))
        {
            $songs = $songs->where('approved', '=', 0);
        }

        if ($this->request->has('hidden'))
        {
            $songs = $songs->where('visibility', '=', 0);
        }

        if ($this->request->has('format'))
        {
            switch ($this->request->input('format')) {
                case 'hls':
                    $songs = $songs->where('hls', '=', 1);
                    break;
                case 'mp3':
                    $songs = $songs->where('mp3', '=', 1);
                    break;
                case 'hd':
                    $songs = $songs->where('hd', '=', 1);
                    break;
            }
        }

        if ($request->has('approved'))
        {
            $songs->orderBy('approved', $request->input('approved'));
        }

        if ($request->has('loves'))
        {
            $songs->orderBy('loves', $request->input('loves'));
        }

        if ($request->has('plays'))
        {
            $songs->orderBy('plays', $request->input('plays'));
        }

        if ($request->has('title'))
        {
            $songs->orderBy('title', $request->input('title'));
        }

        if ($this->request->input('albumIds') && is_array($this->request->input('albumIds')))
        {
            $songs = $songs->leftJoin('album_songs', 'album_songs.song_id', '=', 'songs.id')
                ->select('songs.*', 'album_songs.id as host_id');
            $songs = $songs->where(function ($query) {
                foreach($this->request->input('albumIds') as $index => $albumId) {
                    if($index == 0) {
                        $query->where('album_songs.album_id', '=', 35);
                    } else {
                        $query->orWhere('album_songs.album_id', '=', $albumId);
                    }
                }
            });
        }

        $total_songs = $songs->count();

        if ($this->request->has('results_per_page'))
        {
            $songs = $songs->paginate(intval($this->request->input('results_per_page')));
        } else {
            $songs = $songs->paginate(20);
        }

        return view('backend.songs.index')
            ->with('songs', $songs)
            ->with('total_songs', $total_songs);
    }

    public function delete()
    {
        $song = Song::withoutGlobalScopes()->findOrFail($this->request->route('id'));
        $song->delete();

        return redirect()->back()->with('status', 'success')->with('message', 'Songs successfully deleted!');
    }

    public function add()
    {
        return view('backend.songs.form');
    }

    public function addPost()
    {
        $this->request->validate([
            'title' => 'required|string|max:100',
            'artistIds' => 'required|array',
            'albumIds' => 'nullable|array',
            'released_at' => 'nullable|date_format:m/d/Y',
            'price' => 'nullable|numeric'
        ]);

        $song = new Song();

        $song->title = $this->request->input('title');
        $artistIds = $this->request->input('artistIds');
        $albumIds = $this->request->input('albumIds');
        $song->description = $this->request->input('description');
        $song->price = $this->request->input('price');

        $genre = $this->request->input('genre');
        $mood = $this->request->input('mood');

        $song->released_at = Carbon::parse();
        if($this->request->input('released_at'))
        {
            $song->released_at = Carbon::parse($this->request->input('released_at'));
        } else {
            $song->released_at = null;
        }

        $song->copyright = $this->request->input('copyright');
        $song->allow_comments = $this->request->input('allow_comments');

        if(! $song->approved &&  $this->request->input('approved')) {
            try {
                (new Email)->approvedSong($song->user, $song);
            } catch (\Exception $e) {

            }
        }

        $song->approved = $this->request->input('approved');

        if(is_array($genre))
        {
            $song->genre = implode(",", $this->request->input('genre'));
        }

        if(is_array($mood))
        {
            $song->mood = implode(",", $this->request->input('mood'));
        }

        if(is_array($artistIds))
        {
            $song->artistIds = implode(",", $this->request->input('artistIds'));
        }

        $song->selling = $this->request->input('selling') ? 1 : 0;

        if(isset($song->bpm) && $this->request->input('bpm'))
        {
            $song->bpm = intval($this->request->input('bpm'));
        }

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);

            $song->clearMediaCollection('artwork');
            $song->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        if($this->request->input('group_extra')) {
            $group_regel = array ();

            foreach ( $this->request->input('group_extra') as $key => $value ) {
                if( $value ) $group_regel[] = intval( $key ) . ':' . intval( $value );
            }

            if( count( $group_regel ) ) $group_regel = implode( "||", $group_regel );
            else $group_regel = null;

            $song->access = $group_regel;
        }


        $tags = $this->request->input('tags');

        if(is_array($tags))
        {
            $tags = implode(",", $this->request->input('tags'));
            DB::table('song_tags')->where('song_id', $this->request->route('id'))->delete();

            if( $tags ) {
                $tags = explode( ",", $tags );
                foreach ( $tags as $tag ) {
                    DB::table('song_tags')->insert([
                        'song_id' => $this->request->route('id'),
                        'tag' => $tag
                    ]);
                }
            }
        }


        $song->user_id = auth()->user()->id;
        $song->approved = 1;

        $song->save();

        if(is_array($albumIds)) {
            DB::table('album_songs')->where('song_id', '=', $song->id)->delete();
            foreach($albumIds as $album_id) {
                DB::table('album_songs')->insert(
                    ['song_id' => $song->id, 'album_id' => $album_id]
                );
            }
        }

        if($this->request->input('youtube_id')) {
            if(isset($song->log)) {
                $song->log->youtube = $this->request->input('youtube_id');
                $song->log->save();
            } else {
                $log = new SongLog();
                $log->song_id = $song->id;
                $log->youtube = $this->request->input('youtube_id');
                $log->save();
            }
        }

        if ($this->request->hasFile('file')) {
            $this->handleSongFile($song, $this->request);
        }

        return redirect()->route('backend.songs')->with('status', 'success')->with('message', 'Song successfully added!');
    }

    public function edit()
    {
        $song = Song::withoutGlobalScopes()->findOrFail($this->request->route('id'));
        $lyric = Lyric::withoutGlobalScopes()->where('song_id', $this->request->route('id'))->first();
        $options = groupPermission($song->access);

        return view('backend.songs.form')
            ->with('song', $song)
            ->with('lyric', $lyric)
            ->with('options', $options);
    }

    public function editPost()
    {
        $this->request->validate([
            'title' => 'required|string|max:100',
            'artistIds' => 'required|array',
            'albumIds' => 'nullable|array',
            'released_at' => 'nullable|date_format:m/d/Y',
            'price' => 'nullable|numeric'
        ]);

        $song = Song::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        $song->title = $this->request->input('title');
        $artistIds = $this->request->input('artistIds');
        $albumIds = $this->request->input('albumIds');
        $song->description = $this->request->input('description');
        $song->price = $this->request->input('price');

        if(is_array($albumIds)) {
            DB::table('album_songs')->where('song_id', '=', $song->id)->delete();
            foreach($albumIds as $album_id) {
                DB::table('album_songs')->insert(
                    ['song_id' => $song->id, 'album_id' => $album_id]
                );
            }
        }

        $genre = $this->request->input('genre');
        $mood = $this->request->input('mood');

        $song->released_at = Carbon::parse();
        if($this->request->input('released_at'))
        {
            $song->released_at = Carbon::parse($this->request->input('released_at'));
        } else {
            $song->released_at = null;
        }

        $song->copyright = $this->request->input('copyright');
        $song->allow_comments = $this->request->input('allow_comments');

        if(! $song->approved &&  $this->request->input('approved')) {
            try {
                (new Email)->approvedSong($song->user, $song);
            } catch (\Exception $e) {

            }
        }

        $song->approved = $this->request->input('approved');

        if(is_array($genre))
        {
            $song->genre = implode(",", $this->request->input('genre'));
        }

        if(is_array($mood))
        {
            $song->mood = implode(",", $this->request->input('mood'));
        }

        if(is_array($artistIds))
        {
            $song->artistIds = implode(",", $this->request->input('artistIds'));
        }

        $song->selling = $this->request->input('selling') ? 1 : 0;

        if(isset($song->bpm) && $this->request->input('bpm'))
        {
            $song->bpm = intval($this->request->input('bpm'));
        }

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);

            $song->clearMediaCollection('artwork');
            $song->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        if($this->request->input('group_extra')) {
            $group_regel = array ();

            foreach ( $this->request->input('group_extra') as $key => $value ) {
                if( $value ) $group_regel[] = intval( $key ) . ':' . intval( $value );
            }

            if( count( $group_regel ) ) $group_regel = implode( "||", $group_regel );
            else $group_regel = null;

            $song->access = $group_regel;
        }


        $tags = $this->request->input('tags');

        if(is_array($tags))
        {
            $tags = implode(",", $this->request->input('tags'));
            DB::table('song_tags')->where('song_id', $this->request->route('id'))->delete();

            if( $tags ) {
                $tags = explode( ",", $tags );
                foreach ( $tags as $tag ) {
                    DB::table('song_tags')->insert([
                        'song_id' => $this->request->route('id'),
                        'tag' => $tag
                    ]);
                }
            }
        }

        $song->save();

        if ($this->request->input('lyrics')){
            $current = Lyric::withoutGlobalScopes()->where('song_id', $this->request->route('id'))->first();
            if(isset($current->id)) {
                $lyric = $current;
                $lyric->lyrics = $this->request->input('lyrics');
                $lyric->save();
            } else {
                $lyric = new Lyric();
                $lyric->song_id = $this->request->route('id');
                $lyric->lyrics = $this->request->input('lyrics');
                $lyric->save();
            }
        }

        if($this->request->input('youtube_id')) {
            if(isset($song->log)) {
                $song->log->youtube = $this->request->input('youtube_id');
                $song->log->save();
            } else {
                $log = new SongLog();
                $log->song_id = $song->id;
                $log->youtube = $this->request->input('youtube_id');
                $log->save();
            }
        }

        if ($this->request->hasFile('file')) {
            $song->mp3 = 0;
            $song->hd = 0;
            $song->hls = 0;
            $song->preview = 0;
            $song->clearMediaCollection('audio');
            $song->clearMediaCollection('preview');
            $song->save();

            $this->handleSongFile($song, $this->request);

        }

        return redirect()->route('backend.songs')->with('status', 'success')->with('message', 'Song successfully updated!');
    }

    public function editTitlePost()
    {
        $this->request->validate([
            'title' => 'required|string|max:100'
        ]);

        $song = Song::withoutGlobalScopes()->findOrFail($this->request->input('id'));
        $song->title = $this->request->input('title');
        $song->save();

        return response()->json($song);
    }

    public function reject()
    {
        $this->request->validate([
            'comment' => 'nullable|string',
        ]);
        $song = Song::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        (new Email)->rejectedAlbum($song->user, $song, $this->request->input('comment'));

        Song::withoutGlobalScopes()->where('id', $this->request->route('id'))->delete();
        return redirect()->route('backend.songs')->with('status', 'success')->with('message', 'Song successfully rejected!');
    }

    public function massAction()
    {
        $this->request->validate([
            'action' => 'required|string',
            'ids' => 'required|array',
        ]);

        if($this->request->input('action') == 'add_genre') {
            $message = 'Add genre';
            $subMessage = 'Add Genre for Chosen Songs (<strong>' . count($this->request->input('ids')) . '</strong>)';
            return view('backend.commons.mass_genre')
                ->with('message', $message)
                ->with('subMessage', $subMessage)
                ->with('action', $this->request->input('action'))
                ->with('ids', $this->request->input('ids'));
        } else if($this->request->input('action') == 'save_add_genre') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $song = Song::find($id);
                if(isset($song->id)){
                    $currentGenre = explode(',', $song->genre);
                    $newGenre = array_unique(array_merge($currentGenre, $this->request->input('genre')));
                    $song->genre = implode(',', $newGenre);
                    $song->save();
                }
            }
            return redirect()->route('backend.songs')->with('status', 'success')->with('message', 'Songs successfully saved!');
        } elseif($this->request->input('action') == 'change_genre') {
            $message = 'Change genre';
            $subMessage = 'Change Genre for Chosen Songs (<strong>' . count($this->request->input('ids')) . '</strong>)';
            return view('backend.commons.mass_genre')
                ->with('message', $message)
                ->with('subMessage', $subMessage)
                ->with('action', $this->request->input('action'))
                ->with('ids', $this->request->input('ids'));
        } else if($this->request->input('action') == 'save_change_genre') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $song = Song::withoutGlobalScopes()->find($id);
                if(isset($song->id)){
                    $song->genre = implode(',', $this->request->input('genre'));
                    $song->save();
                }
            }
            return redirect()->route('backend.songs')->with('status', 'success')->with('message', 'Songs successfully saved!');
        } elseif($this->request->input('action') == 'add_mood') {
            $message = 'Add mood';
            $subMessage = 'Add Mood for Chosen Songs (<strong>' . count($this->request->input('ids')) . '</strong>)';
            return view('backend.commons.mass_mood')
                ->with('message', $message)
                ->with('subMessage', $subMessage)
                ->with('action', $this->request->input('action'))
                ->with('ids', $this->request->input('ids'));
        } else if($this->request->input('action') == 'save_add_mood') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $song = Song::withoutGlobalScopes()->find($id);
                if(isset($song->id)){
                    $currentMood = explode(',', $song->mood);
                    $newMood = array_unique(array_merge($currentMood, $this->request->input('mood')));
                    $song->mood = implode(',', $newMood);
                    $song->save();
                }
            }
            return redirect()->route('backend.songs')->with('status', 'success')->with('message', 'Songs successfully saved!');
        } elseif($this->request->input('action') == 'change_mood') {
            $message = 'Change mood';
            $subMessage = 'Change Mood for Chosen Songs (<strong>' . count($this->request->input('ids')) . '</strong>)';
            return view('backend.commons.mass_mood')
                ->with('message', $message)
                ->with('subMessage', $subMessage)
                ->with('action', $this->request->input('action'))
                ->with('ids', $this->request->input('ids'));
        } elseif($this->request->input('action') == 'change_artist') {
            $message = 'Change artist';
            $subMessage = 'Change Artist for Chosen Songs (<strong>' . count($this->request->input('ids')) . '</strong>)';
            return view('backend.commons.mass_artist')
                ->with('message', $message)
                ->with('subMessage', $subMessage)
                ->with('action', $this->request->input('action'))
                ->with('ids', $this->request->input('ids'));
        } else if($this->request->input('action') == 'save_change_artist') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $song = Song::withoutGlobalScopes()->find($id);
                $artistIds = $this->request->input('artistIds');
                if(isset($song->id)){
                    if(is_array($artistIds))
                    {
                        $song->artistIds = implode(",", $this->request->input('artistIds'));
                    }
                    $song->save();
                }
            }
            return redirect()->route('backend.songs')->with('status', 'success')->with('message', 'Songs successfully saved!');
        } elseif($this->request->input('action') == 'change_album') {
            $message = 'Change album';
            $subMessage = 'Change Album for Chosen Songs (<strong>' . count($this->request->input('ids')) . '</strong>)';
            return view('backend.commons.mass_album')
                ->with('message', $message)
                ->with('subMessage', $subMessage)
                ->with('action', $this->request->input('action'))
                ->with('ids', $this->request->input('ids'));
        } else if($this->request->input('action') == 'save_change_album') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $song = Song::withoutGlobalScopes()->find($id);
                $albumIds = $this->request->input('albumIds');
                if(isset($song->id)){
                    if(is_array($albumIds)) {
                        DB::table('album_songs')->where('song_id', '=', $song->id)->delete();
                        foreach($albumIds as $album_id) {
                            DB::table('album_songs')->insert(
                                ['song_id' => $song->id, 'album_id' => $album_id]
                            );
                        }
                    }
                }
            }
            return redirect()->route('backend.songs')->with('status', 'success')->with('message', 'Songs successfully saved!');
        } else if($this->request->input('action') == 'approve') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $song = Song::withoutGlobalScopes()->find($id);
                if(isset($song->id)){
                    $song->approved = 1;
                    $song->save();
                }
            }
            return redirect()->back()->with('status', 'success')->with('message', 'Songs successfully saved!');
        } else if($this->request->input('action') == 'not_approve') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $song = Song::withoutGlobalScopes()->find($id);
                if(isset($song->id)){
                    $song->approved = 0;
                    $song->save();
                }
            }
            return redirect()->back()->with('status', 'success')->with('message', 'Songs successfully saved!');
        } else if($this->request->input('action') == 'comments') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $song = Song::withoutGlobalScopes()->find($id);
                if(isset($song->id)){
                    $song->allow_comments = 1;
                    $song->save();
                }
            }
            return redirect()->back()->with('status', 'success')->with('message', 'Songs successfully saved!');
        } else if($this->request->input('action') == 'not_comments') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $song = Song::withoutGlobalScopes()->find($id);
                if(isset($song->id)){
                    $song->allow_comments = 0;
                    $song->save();
                }
            }
            return redirect()->back()->with('status', 'success')->with('message', 'Songs successfully saved!');
        } else if($this->request->input('action') == 'clear_count') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $song = Song::withoutGlobalScopes()->find($id);
                if(isset($song->id)){
                    $song->plays = 0;
                    $song->save();
                }
            }
            return redirect()->back()->with('status', 'success')->with('message', 'Songs successfully saved!');
        } else if($this->request->input('action') == 'delete') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $song = Song::withoutGlobalScopes()->where('id', $id)->first();
                $song->delete();
            }
            return redirect()->back()->with('status', 'success')->with('message', 'Songs successfully deleted!');
        }
    }

    public function handleSongFile($song, $request) {
        if (config('settings.ffmpeg') && @shell_exec(env('FFMPEG_PATH') . ' -version')) {
            if (config('settings.max_audio_file_size') == 0) {
                $request->validate([
                    'file.*' => 'required|mimetypes:application/octet-stream,audio/ogg,audio/x-wav,audio/wav,audio/mpeg,audio/flac,audio/x-hx-aac-adts,audio/x-m4a,video/mp4,video/x-ms-wma,audio/ac3,audio/aac',
                ]);
            } else {
                $request->validate([
                    'file.*' => 'required|mimetypes:application/octet-stream,audio/ogg,audio/x-wav,audio/wav,audio/mpeg,audio/flac,audio/x-hx-aac-adts,audio/x-m4a,video/mp4,video/x-ms-wma,audio/ac3,audio/aac|max:' . config('settings.max_audio_file_size', 51200),
                ]);
            }
        } else {
            if (config('settings.max_audio_file_size') == 0) {
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

            if(isset($mp3Info['audio']['id3v2']['title'][0])) {
                $trackInfo = $mp3Info['tags']['id3v2'];
            }elseif(isset($mp3Info['tags']['id3v2']['title'][0])) {
                $trackInfo = $mp3Info['tags']['id3v2'];
            } elseif(isset($mp3Info['tags']['id3v1'])) {
                $trackInfo = $mp3Info['tags']['id3v1'];
            } elseif(isset($mp3Info['id3v2']['comments']['title'][0])) {
                $trackInfo = $mp3Info['id3v2']['comments'];
            }

            $song->duration = $data['playtimeSeconds'];

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

            $song->save();
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
}