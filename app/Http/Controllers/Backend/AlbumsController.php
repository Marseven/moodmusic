<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 09:01
 */

namespace App\Http\Controllers\Backend;

use App\Models\Email;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use View;
use App\Models\Album;
use Image;
use App\Models\Song;

class AlbumsController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(Request $request)
    {
        $albums = Album::withoutGlobalScopes();

        if ($this->request->has('term'))
        {
            $albums = $albums->where('title', 'like', '%' . $this->request->input('term') . '%');
        }

        if ($this->request->input('artistIds') && is_array($this->request->input('artistIds')))
        {
            $albums = $albums->where(function ($query) {
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
            $albums = $albums->where(function ($query) {
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
            $albums = $albums->where('genre', 'REGEXP', '(^|,)(' . implode(',', $this->request->input('genre')) . ')(,|$)');
        }

        if ($this->request->input('mood') && is_array($this->request->input('mood')))
        {
            $albums = $albums->where('mood', 'REGEXP', '(^|,)(' . implode(',', $this->request->input('mood')) . ')(,|$)');
        }

        if ($this->request->input('created_from'))
        {
            $albums = $albums->where('created_at', '>=', Carbon::parse($this->request->input('created_from')));
        }

        if ($this->request->has('created_until'))
        {
            $albums = $albums->where('created_at', '<=', Carbon::parse($this->request->input('created_until')));
        }

        if ($this->request->input('comment_count_from'))
        {
            $albums = $albums->where('comment_count', '>=', intval($this->request->input('comment_count_from')));
        }

        if ($this->request->has('comment_count_until'))
        {
            $albums = $albums->where('comment_count', '<=', intval($this->request->input('comment_count_until')));
        }

        if ($this->request->has('comment_disabled'))
        {
            $albums = $albums->where('allow_comments', '=', 0);
        }

        if ($this->request->has('not_approved'))
        {
            $albums = $albums->where('approved', '=', 0);
        }

        if ($this->request->has('hidden'))
        {
            $albums = $albums->where('visibility', '=', 0);
        }

        if ($request->has('approved'))
        {
            $albums->orderBy('approved', $request->input('approved'));
        }

        if ($request->has('title'))
        {
            $albums->orderBy('title', $request->input('title'));
        }

        if ($this->request->has('results_per_page'))
        {
            $albums = $albums->paginate(intval($this->request->input('results_per_page')));
        } else {
            $albums = $albums->paginate(20);
        }

        return view('backend.albums.index')
            ->with('albums', $albums);
    }

    public function delete()
    {
        Album::withoutGlobalScopes()->where('id', $this->request->route('id'))->delete();
        DB::table('album_songs')->where('album_id', '=', $this->request->route('id'))->delete();
        return redirect()->route('backend.albums')->with('status', 'success')->with('message', 'Album successfully deleted!');
    }

    public function add()
    {
        return view('backend.albums.form');
    }

    public function addPost()
    {
        $this->request->validate([
            'name' => 'required|string',
            'artistIds' => 'required|array',
            'created_at' => 'nullable|date_format:m/d/Y',
            'released_at' => 'nullable|date_format:m/d/Y',
            'price' => 'nullable|numeric'
        ]);

        $album = new Album();

        $album->title = $this->request->input('name');
        $albumIds = $this->request->input('artistIds');
        $genre = $this->request->input('genre');
        $mood = $this->request->input('mood');
        $album->description = $this->request->input('description');
        $album->approved = 1;
        $album->user_id = auth()->user()->id;
        $album->copyright = $this->request->input('copyright') ? $this->request->input('copyright') : '';
        $album->price = $this->request->input('price');
        $album->selling = $this->request->input('selling') ? 1 : 0;

        if($this->request->input('released_at')) {
            $album->released_at = Carbon::parse($this->request->input('released_at'));
        }

        if($this->request->input('created_at')) {
            $album->created_at = Carbon::parse($this->request->input('created_at'));
        }

        if(is_array($genre))
        {
            $album->genre = implode(",", $this->request->input('genre'));

        }

        if(is_array($mood))
        {
            $album->mood = implode(",", $this->request->input('mood'));

        }

        if(is_array($albumIds))
        {
            $album->artistIds = implode(",", $this->request->input('artistIds'));

        }

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);

            $album->clearMediaCollection('artwork');
            $album->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        $album->save();

        if($this->request->input('group_extra')) {
            $group_regel = array ();

            foreach ( $this->request->input('group_extra') as $key => $value ) {
                if( $value ) $group_regel[] = intval( $key ) . ':' . intval( $value );
            }

            if( count( $group_regel ) ) $group_regel = implode( "||", $group_regel );
            else $group_regel = null;

            $album->access = $group_regel;
        }

        return redirect()->route('backend.albums')->with('status', 'success')->with('message', 'Album successfully added!');
    }

    public function edit()
    {
        $album = Album::withoutGlobalScopes()->findOrFail($this->request->route('id'));
        $album->setRelation('songs', $album->songs()->withoutGlobalScopes()->get());
        $options = groupPermission($album->access);

        return view('backend.albums.form')
            ->with('album', $album)
            ->with('options', $options);
    }

    public function editPost()
    {
        $this->request->validate([
            'name' => 'required|string',
            'artistIds' => 'required|array',
            'created_at' => 'nullable|date_format:m/d/Y',
            'released_at' => 'nullable|date_format:m/d/Y',
            'price' => 'nullable|numeric'
        ]);

        $album = Album::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);

            $album->clearMediaCollection('artwork');
            $album->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        $album->title = $this->request->input('name');

        $albumIds = $this->request->input('artistIds');
        $genre = $this->request->input('genre');
        $mood = $this->request->input('mood');
        $album->description = $this->request->input('description');
        $album->copyright = $this->request->input('copyright') ? $this->request->input('copyright') : '';
        $album->price = $this->request->input('price');
        $album->selling = $this->request->input('selling') ? 1 : 0;

        $album->released_at = Carbon::parse($this->request->input('released_at'));
        $album->created_at = Carbon::parse($this->request->input('created_at'));

        if(! $album->approved && $this->request->input('approved')) {
            try {
                (new Email)->approvedAlbum($album->user, $album);
            } catch (\Exception $e) {

            }
        }

        $album->approved = $this->request->input('approved');

        if(is_array($genre))
        {
            $album->genre = implode(",", $this->request->input('genre'));
        }

        if(is_array($mood))
        {
            $album->mood = implode(",", $this->request->input('mood'));
        }

        if(is_array($albumIds))
        {
            $album->artistIds = implode(",", $this->request->input('artistIds'));

        }

        if($this->request->input('group_extra')) {
            $group_regel = array ();

            foreach ( $this->request->input('group_extra') as $key => $value ) {
                if( $value ) $group_regel[] = intval( $key ) . ':' . intval( $value );
            }

            if( count( $group_regel ) ) $group_regel = implode( "||", $group_regel );
            else $group_regel = null;

            $album->access = $group_regel;

            foreach($album->songs()->withoutGlobalScopes()->get() as $song) {
                $song->access = $group_regel;
                $song->save();
            }
        }

        $album->save();

        if($this->request->input('update-song-artwork')) {
            if($album->getFirstMediaUrl('artwork')) {
                $album_artwork = $album->getMedia('artwork')->first();
                foreach($album->songs()->withoutGlobalScopes()->get() as $song) {
                    $song->clearMediaCollection('artwork');
                    $album_artwork->copy($song, 'artwork');
                }
            }
        }

        return redirect()->route('backend.albums')->with('status', 'success')->with('message', 'Album successfully edited!');
    }

    public function trackList()
    {
        $album = Album::withoutGlobalScopes()->findOrFail($this->request->route('id'));
        $album->setRelation('songs', $album->songs()->withoutGlobalScopes()->get());
        return view('backend.albums.tracklist')
            ->with('album', $album);
    }

    public function trackListMassAction()
    {
        if( ! $this->request->input('action') ) {
            $songIds = $this->request->input('songIds');
            foreach ($songIds as $index => $songId) {
                DB::table('album_songs')
                    ->where('album_id', $this->request->route('id'))
                    ->where('song_id', $songId)
                    ->update(['priority' => $index]);
            }
            return redirect()->back()->with('status', 'success')->with('message', 'Priority successfully changed!');
        } else {
            $this->request->validate([
                'action' => 'required|string',
                'ids' => 'required|array',
            ]);

            if($this->request->input('action') == 'remove_from_album') {
                $ids = $this->request->input('ids');
                foreach($ids as $id) {
                    $song = Song::withoutGlobalScopes()->where('id', $id)->first();
                    DB::table('album_songs')->where('song_id', $song->id)->delete();
                }
                return redirect()->back()->with('status', 'success')->with('message', 'Songs successfully removed from the album!');
            } else if($this->request->input('action') == 'delete') {
                $ids = $this->request->input('ids');
                foreach($ids as $id) {
                    $song = Song::withoutGlobalScopes()->where('id', $id)->first();
                    $song->delete();
                }
                return redirect()->back()->with('status', 'success')->with('message', 'Songs successfully deleted!');
            }
        }

    }

    public function upload()
    {
        $album = Album::withoutGlobalScopes()->findOrFail($this->request->route('id'));
        return view('backend.albums.upload')
            ->with('album', $album);;
    }

    public function reject()
    {
        $this->request->validate([
            'comment' => 'nullable|string',
        ]);
        $album = Album::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        (new Email)->rejectedAlbum($album->user, $album, $this->request->input('comment'));

        Album::withoutGlobalScopes()->where('id', $this->request->route('id'))->delete();
        return redirect()->route('backend.albums')->with('status', 'success')->with('message', 'Album successfully rejected!');
    }

    public function massAction()
    {
        $this->request->validate([
            'action' => 'required|string',
            'ids' => 'required|array',
        ]);

        if($this->request->input('action') == 'add_genre') {
            $message = 'Add genre';
            $subMessage = 'Add Genre for Chosen Albums (<strong>' . count($this->request->input('ids')) . '</strong>)';
            return view('backend.commons.mass_genre')
                ->with('message', $message)
                ->with('subMessage', $subMessage)
                ->with('action', $this->request->input('action'))
                ->with('ids', $this->request->input('ids'));
        } else if($this->request->input('action') == 'save_add_genre') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $album = Album::find($id);
                if(isset($album->id)){
                    $currentGenre = explode(',', $album->genre);
                    $newGenre = array_unique(array_merge($currentGenre, $this->request->input('genre')));
                    $album->genre = implode(',', $newGenre);
                    $album->save();
                }
            }
            return redirect()->route('backend.albums')->with('status', 'success')->with('message', 'Albums successfully saved!');
        } elseif($this->request->input('action') == 'change_genre') {
            $message = 'Change genre';
            $subMessage = 'Change Genre for Chosen Albums (<strong>' . count($this->request->input('ids')) . '</strong>)';
            return view('backend.commons.mass_genre')
                ->with('message', $message)
                ->with('subMessage', $subMessage)
                ->with('action', $this->request->input('action'))
                ->with('ids', $this->request->input('ids'));
        } else if($this->request->input('action') == 'save_change_genre') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $album = Album::withoutGlobalScopes()->find($id);
                if(isset($album->id)){
                    $album->genre = implode(',', $this->request->input('genre'));
                    $album->save();
                }
            }
            return redirect()->route('backend.albums')->with('status', 'success')->with('message', 'Albums successfully saved!');
        } elseif($this->request->input('action') == 'add_mood') {
            $message = 'Add mood';
            $subMessage = 'Add Mood for Chosen Albums (<strong>' . count($this->request->input('ids')) . '</strong>)';
            return view('backend.commons.mass_mood')
                ->with('message', $message)
                ->with('subMessage', $subMessage)
                ->with('action', $this->request->input('action'))
                ->with('ids', $this->request->input('ids'));
        } else if($this->request->input('action') == 'save_add_mood') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $album = Album::withoutGlobalScopes()->find($id);
                if(isset($album->id)){
                    $currentMood = explode(',', $album->mood);
                    $newMood = array_unique(array_merge($currentMood, $this->request->input('genre')));
                    $album->mood = implode(',', $newMood);
                    $album->save();
                }
            }
            return redirect()->route('backend.albums')->with('status', 'success')->with('message', 'Albums successfully saved!');
        } elseif($this->request->input('action') == 'change_mood') {
            $message = 'Change mood';
            $subMessage = 'Change Mood for Chosen Albums (<strong>' . count($this->request->input('ids')) . '</strong>)';
            return view('backend.commons.mass_mood')
                ->with('message', $message)
                ->with('subMessage', $subMessage)
                ->with('action', $this->request->input('action'))
                ->with('ids', $this->request->input('ids'));
        } else if($this->request->input('action') == 'save_change_mood') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $album = Album::withoutGlobalScopes()->find($id);
                if(isset($album->id)){
                    $album->mood = implode(',', $this->request->input('mood'));
                    $album->save();
                }
            }
            return redirect()->route('backend.albums')->with('status', 'success')->with('message', 'Albums successfully saved!');
        } elseif($this->request->input('action') == 'change_artist') {
            $message = 'Change artist';
            $subMessage = 'Change Album for Chosen Albums (<strong>' . count($this->request->input('ids')) . '</strong>)';
            return view('backend.commons.mass_artist')
                ->with('message', $message)
                ->with('subMessage', $subMessage)
                ->with('action', $this->request->input('action'))
                ->with('ids', $this->request->input('ids'));
        } else if($this->request->input('action') == 'save_change_artist') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $album = Album::withoutGlobalScopes()->find($id);
                $artistIds = $this->request->input('artistIds');
                if(isset($album->id)){
                    if(is_array($artistIds))
                    {
                        $album->artistIds = implode(",", $this->request->input('artistIds'));
                    }
                    $album->save();
                }
            }
            return redirect()->route('backend.albums')->with('status', 'success')->with('message', 'Albums successfully saved!');
        } else if($this->request->input('action') == 'approve') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $album = Album::withoutGlobalScopes()->find($id);
                if(isset($album->id)){
                    $album->approved = 1;
                    $album->save();
                }
            }
            return redirect()->route('backend.albums')->with('status', 'success')->with('message', 'Albums successfully saved!');
        } else if($this->request->input('action') == 'not_approve') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $album = Album::withoutGlobalScopes()->find($id);
                if(isset($album->id)){
                    $album->approved = 0;
                    $album->save();
                }
            }
            return redirect()->route('backend.albums')->with('status', 'success')->with('message', 'Albums successfully saved!');
        } else if($this->request->input('action') == 'comments') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $album = Album::withoutGlobalScopes()->find($id);
                if(isset($album->id)){
                    $album->allow_comments = 1;
                    $album->save();
                }
            }
            return redirect()->route('backend.albums')->with('status', 'success')->with('message', 'Albums successfully saved!');
        } else if($this->request->input('action') == 'not_comments') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $album = Album::withoutGlobalScopes()->find($id);
                if(isset($album->id)){
                    $album->allow_comments = 0;
                    $album->save();
                }
            }
            return redirect()->route('backend.albums')->with('status', 'success')->with('message', 'Albums successfully saved!');
        } else if($this->request->input('action') == 'clear_count') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $album = Album::withoutGlobalScopes()->find($id);
                if(isset($album->id)){
                    $album->plays = 0;
                    $album->save();
                }
            }
            return redirect()->route('backend.albums')->with('status', 'success')->with('message', 'Albums successfully saved!');
        } else if($this->request->input('action') == 'delete') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $album = Album::withoutGlobalScopes()->where('id', $id)->first();
                $album->delete();
            }
            return redirect()->route('backend.albums')->with('status', 'success')->with('message', 'Albums successfully deleted!');
        }
    }
}