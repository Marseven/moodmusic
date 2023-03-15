<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 09:01
 */

namespace App\Http\Controllers\Backend;

use App\Models\Song;
use Illuminate\Http\Request;
use DB;
use View;
use App\Models\Playlist;
use Auth;
use Image;
use Carbon\Carbon;

class PlaylistsController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $playlists = Playlist::withoutGlobalScopes();

        if ($this->request->has('term'))
        {
            $playlists = $playlists->where('title', 'like', '%' . $this->request->input('term') . '%');
        }

        if ($this->request->input('userIds') && is_array($this->request->input('userIds')))
        {
            $playlists = $playlists->where(function ($query) {
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
            $playlists = $playlists->where('genre', 'REGEXP', '(^|,)(' . implode(',', $this->request->input('genre')) . ')(,|$)');
        }

        if ($this->request->input('mood') && is_array($this->request->input('mood')))
        {
            $playlists = $playlists->where('mood', 'REGEXP', '(^|,)(' . implode(',', $this->request->input('mood')) . ')(,|$)');
        }

        if ($this->request->input('created_from'))
        {
            $playlists = $playlists->where('created_at', '>=', Carbon::parse($this->request->input('created_from')));
        }

        if ($this->request->has('created_until'))
        {
            $playlists = $playlists->where('created_at', '<=', Carbon::parse($this->request->input('created_until')));
        }

        if ($this->request->input('comment_count_from'))
        {
            $playlists = $playlists->where('comment_count', '>=', intval($this->request->input('comment_count_from')));
        }

        if ($this->request->has('comment_count_until'))
        {
            $playlists = $playlists->where('comment_count', '<=', intval($this->request->input('comment_count_until')));
        }

        if ($this->request->has('comment_disabled'))
        {
            $playlists = $playlists->where('allow_comments', '=', 0);
        }

        if ($this->request->has('not_approved'))
        {
            $playlists = $playlists->where('approved', '=', 0);
        }

        if ($this->request->has('hidden'))
        {
            $playlists = $playlists->where('visibility', '=', 0);
        }

        if ($this->request->has('loves'))
        {
            $playlists->orderBy('loves', $this->request->input('loves'));
        }

        if ($this->request->has('plays'))
        {
            $playlists->orderBy('plays', $this->request->input('plays'));
        }

        if ($this->request->has('title'))
        {
            $playlists->orderBy('title', $this->request->input('title'));
        }

        if ($this->request->has('results_per_page'))
        {
            $playlists = $playlists->paginate(intval($this->request->input('results_per_page')));
        } else {
            $playlists = $playlists->paginate(20);
        }

        return view('backend.playlists.index')
            ->with('playlists', $playlists);
    }

    public function delete()
    {
        Playlist::withoutGlobalScopes()->where('id', $this->request->route('id'))->delete();
        DB::table('playlist_songs')->where('playlist_id', '=', $this->request->route('id'))->delete();
        return redirect()->route('backend.playlists')->with('status', 'success')->with('message', 'Playlist successfully deleted!');
    }

    public function edit()
    {
        $playlist = Playlist::findOrFail($this->request->route('id'));

        return view('backend.playlists.edit')->with('playlist', $playlist);
    }

    public function savePost()
    {
        $this->request->validate([
            'title' => 'required',
            'genre' => 'array',
            'mood' => 'array',
        ]);

        if(request()->route()->getName() == 'backend.playlists.add.post') {
            $playlist = new Playlist();
        } else {
            $playlist = Playlist::findOrFail($this->request->route('id'));
        }

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);

            if(request()->route()->getName() == 'backend.playlists.edit.post') {
                $playlist->clearMediaCollection('artwork');
            }

            $playlist->clearMediaCollection('artwork');
            $playlist->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        $playlist->title = $this->request->input('title');
        $playlist->description = $this->request->input('description');

        if(is_array($this->request->input('genre')))
        {
            $playlist->genre = implode(",", $this->request->input('genre'));
        }

        if(is_array($playlist->mood))
        {
            $playlist->mood = implode(",", $this->request->input('mood'));
        }

        $playlist->save();

        return redirect()->route('backend.playlists')->with('status', 'success')->with('message', 'Playlist successfully edited!');
    }

    public function trackList()
    {
        $playlist = Playlist::findOrFail($this->request->route('id'));
        $playlist->setRelation('songs', $playlist->songs()->get());
        return view('backend.playlists.tracklist')->with('playlist', $playlist);
    }
    public function trackListMassAction()
    {
        if( ! $this->request->input('action') ) {
            $songIds = $this->request->input('songIds');
            foreach ($songIds as $index => $songId) {
                DB::table('playlist_songs')
                    ->where('playlist_id', $this->request->route('id'))
                    ->where('song_id', $songId)
                    ->update(['priority' => $index]);
            }
            return redirect()->back()->with('status', 'success')->with('message', 'Priority successfully changed!');
        } else {
            $this->request->validate([
                'action' => 'required|string',
                'ids' => 'required|array',
            ]);

            if($this->request->input('action') == 'remove_from_playlist') {
                $ids = $this->request->input('ids');
                foreach($ids as $id) {
                    $song = Song::withoutGlobalScopes()->where('id', $id)->first();
                    DB::table('playlist_songs')->where('song_id', $song->id)->delete();
                }
                return redirect()->back()->with('status', 'success')->with('message', 'Songs successfully removed from the playlist!');
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

    public function massAction()
    {
        $this->request->validate([
            'action' => 'required|string',
            'ids' => 'required|array',
            'genre' => 'nullable|array',
        ]);

        if($this->request->input('action') == 'add_genre') {
            $message = 'Add genre';
            $subMessage = 'Add Genre for Chosen Playlists (<strong>' . count($this->request->input('ids')) . '</strong>)';
            return view('backend.commons.mass_genre')
                ->with('message', $message)
                ->with('subMessage', $subMessage)
                ->with('action', $this->request->input('action'))
                ->with('ids', $this->request->input('ids'));
        } else if($this->request->input('action') == 'save_add_genre') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $playlist = Playlist::find($id);
                if(isset($playlist->id)){
                    $currentGenre = explode(',', $playlist->genre);
                    $newGenre = array_unique(array_merge($currentGenre, $this->request->input('genre')));
                    $playlist->genre = implode(',', $newGenre);
                    $playlist->save();
                }
            }
            return redirect()->route('backend.playlists')->with('status', 'success')->with('message', 'Playlists successfully saved!');
        } elseif($this->request->input('action') == 'change_genre') {
            $message = 'Change genre';
            $subMessage = 'Change Genre for Chosen Playlists (<strong>' . count($this->request->input('ids')) . '</strong>)';
            return view('backend.commons.mass_genre')
                ->with('message', $message)
                ->with('subMessage', $subMessage)
                ->with('action', $this->request->input('action'))
                ->with('ids', $this->request->input('ids'));
        } else if($this->request->input('action') == 'save_change_genre') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $playlist = Playlist::withoutGlobalScopes()->find($id);
                if(isset($playlist->id)){
                    $playlist->genre = implode(',', $this->request->input('genre'));
                    $playlist->save();
                }
            }
            return redirect()->route('backend.playlists')->with('status', 'success')->with('message', 'Playlists successfully saved!');
        } elseif($this->request->input('action') == 'add_mood') {
            $message = 'Add mood';
            $subMessage = 'Add Mood for Chosen Playlists (<strong>' . count($this->request->input('ids')) . '</strong>)';
            return view('backend.commons.mass_mood')
                ->with('message', $message)
                ->with('subMessage', $subMessage)
                ->with('action', $this->request->input('action'))
                ->with('ids', $this->request->input('ids'));
        } else if($this->request->input('action') == 'save_add_mood') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $playlist = Playlist::withoutGlobalScopes()->find($id);
                if(isset($playlist->id)){
                    $currentMood = explode(',', $playlist->mood);
                    $newMood = array_unique(array_merge($currentMood, $this->request->input('mood')));
                    $playlist->mood = implode(',', $newMood);
                    $playlist->save();
                }
            }
            return redirect()->route('backend.playlists')->with('status', 'success')->with('message', 'Playlists successfully saved!');
        } elseif($this->request->input('action') == 'change_mood') {
            $message = 'Change mood';
            $subMessage = 'Change Mood for Chosen Playlists (<strong>' . count($this->request->input('ids')) . '</strong>)';
            return view('backend.commons.mass_mood')
                ->with('message', $message)
                ->with('subMessage', $subMessage)
                ->with('action', $this->request->input('action'))
                ->with('ids', $this->request->input('ids'));
        } else if($this->request->input('action') == 'save_change_mood') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $playlist = Playlist::withoutGlobalScopes()->find($id);
                if(isset($playlist->id)){
                    $playlist->mood = implode(',', $this->request->input('mood'));
                    $playlist->save();
                }
            }
            return redirect()->route('backend.playlists')->with('status', 'success')->with('message', 'Playlists successfully saved!');
        } else if($this->request->input('action') == 'visibility') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $playlist = Playlist::withoutGlobalScopes()->find($id);
                if(isset($playlist->id)){
                    $playlist->visibility = 1;
                    $playlist->save();
                }
            }
            return redirect()->route('backend.playlists')->with('status', 'success')->with('message', 'Playlists successfully saved!');
        } else if($this->request->input('action') == 'private') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $playlist = Playlist::withoutGlobalScopes()->find($id);
                if(isset($playlist->id)){
                    $playlist->visibility = 0;
                    $playlist->save();
                }
            }
            return redirect()->route('backend.playlists')->with('status', 'success')->with('message', 'Playlists successfully saved!');
        } else if($this->request->input('action') == 'comments') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $playlist = Playlist::withoutGlobalScopes()->find($id);
                if(isset($playlist->id)){
                    $playlist->allow_comments = 1;
                    $playlist->save();
                }
            }
            return redirect()->route('backend.playlists')->with('status', 'success')->with('message', 'Playlists successfully saved!');
        } else if($this->request->input('action') == 'not_comments') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $playlist = Playlist::withoutGlobalScopes()->find($id);
                if(isset($playlist->id)){
                    $playlist->allow_comments = 0;
                    $playlist->save();
                }
            }
            return redirect()->route('backend.playlists')->with('status', 'success')->with('message', 'Playlists successfully saved!');
        } else if($this->request->input('action') == 'delete') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $playlist = Playlist::withoutGlobalScopes()->where('id', $id)->first();
                $playlist->delete();
            }
            return redirect()->route('backend.playlists')->with('status', 'success')->with('message', 'Playlists successfully deleted!');
        }
    }
}