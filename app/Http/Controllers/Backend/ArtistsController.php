<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 09:02
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use DB;
use View;
use Config;
use App\Models\Artist;
use App\Models\Song;
use App\Models\Album;
use Auth;
use Image;
use Carbon\Carbon;

class ArtistsController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $artists = Artist::withoutGlobalScopes();

        if ($this->request->has('term'))
        {
            $artists = $artists->where('name', 'like', '%' . $this->request->input('term') . '%');
        }

        if ($this->request->input('artistIds') && is_array($this->request->input('artistIds')))
        {
            $artists = $artists->where(function ($query) {
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
            $artists = $artists->where(function ($query) {
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
            $artists = $artists->where('genre', 'REGEXP', '(^|,)(' . implode(',', $this->request->input('genre')) . ')(,|$)');
        }

        if ($this->request->input('mood') && is_array($this->request->input('mood')))
        {
            $artists = $artists->where('mood', 'REGEXP', '(^|,)(' . implode(',', $this->request->input('mood')) . ')(,|$)');
        }

        if ($this->request->input('created_from'))
        {
            $artists = $artists->where('created_at', '>=', Carbon::parse($this->request->input('created_from')));
        }

        if ($this->request->has('created_until'))
        {
            $artists = $artists->where('created_at', '<=', Carbon::parse($this->request->input('created_until')));
        }

        if ($this->request->input('comment_count_from'))
        {
            $artists = $artists->where('comment_count', '>=', intval($this->request->input('comment_count_from')));
        }

        if ($this->request->has('comment_count_until'))
        {
            $artists = $artists->where('comment_count', '<=', intval($this->request->input('comment_count_until')));
        }

        if ($this->request->has('comment_disabled'))
        {
            $artists = $artists->where('allow_comments', '=', 0);
        }

        if ($this->request->has('verified'))
        {
            $artists = $artists->where('verified', '=', 1);
        }

        if ($this->request->has('hidden'))
        {
            $artists = $artists->where('visibility', '=', 0);
        }

        if ($this->request->has('loves'))
        {
            $artists->orderBy('loves', $this->request->input('loves'));
        }

        if ($this->request->has('title'))
        {
            $artists->orderBy('title', $this->request->input('title'));
        }

        if ($this->request->has('results_per_page'))
        {
            $artists = $artists->paginate(intval($this->request->input('results_per_page')));
        } else {
            $artists = $artists->paginate(20);
        }

        return view('backend.artists.index')
            ->with('artists', $artists);
    }

    public function delete()
    {
        $artist = Artist::findOrFail($this->request->route('id'));

        $artist->delete();

        return redirect()->route('backend.artists')->with('status', 'success')->with('message', 'Artist successfully deleted!');
    }

    public function add()
    {
        return view('backend.artists.add');
    }

    public function addPost()
    {
        $this->request->validate([
            'name' => 'required|string',
            'genre' => 'array',
            'mood' => 'array',
            'artwork' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
        ]);

        $artist = new Artist();

        $artist->name = $this->request->input('name');
        $genre = $this->request->input('genre');
        $mood = $this->request->input('mood');

        if(is_array($genre))
        {
            $artist->genre = implode(",", $this->request->input('genre'));
        }

        if(is_array($mood))
        {
            $artist->mood = implode(",", $this->request->input('mood'));
        }

        $artist->bio = $this->request->input('bio');

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);

            $artist->clearMediaCollection('artwork');
            $artist->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        $artist->save();

        return redirect()->route('backend.artists')->with('status', 'success')->with('message', 'Artist successfully added!');
    }

    public function edit()
    {
        $artist = Artist::findOrFail($this->request->route('id'));

        $artist->setRelation('albums', $artist->albums()->withoutGlobalScopes()->paginate(20));
        $artist->setRelation('songs', $artist->songs()->withoutGlobalScopes()->paginate(20));

        return view('backend.artists.edit')
            ->with('artist', $artist);
    }

    public function editPost()
    {
        $this->request->validate([
            'name' => 'required|string',
            'genre' => 'array',
            'mood' => 'array',
        ]);

        $artist = Artist::findOrFail($this->request->route('id'));

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);

            $artist->clearMediaCollection('artwork');
            $artist->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        $artist->name = $this->request->input('name');
        $genre = $this->request->input('genre');

        if(is_array($genre))
        {
            $artist->genre = implode(",", $this->request->input('genre'));

        }

        $mood = $this->request->input('mood');

        if(is_array($mood))
        {
            $artist->mood = implode(",", $this->request->input('mood'));

        }

        $artist->bio = $this->request->input('bio');

        $artist->save();

        return redirect()->route('backend.artists')->with('status', 'success')->with('message', 'Artist successfully updated!');

    }

    public function upload()
    {
        $artist = Artist::findOrFail($this->request->route('id'));

        return view('backend.artists.upload')
            ->with('artist', $artist);;
    }

    public function massAction()
    {
        $this->request->validate([
            'action' => 'required|string',
            'ids' => 'required|array',
        ]);

        if($this->request->input('action') == 'add_genre') {
            $message = 'Add genre';
            $subMessage = 'Add Genre for Chosen Artists (<strong>' . count($this->request->input('ids')) . '</strong>)';
            return view('backend.commons.mass_genre')
                ->with('message', $message)
                ->with('subMessage', $subMessage)
                ->with('action', $this->request->input('action'))
                ->with('ids', $this->request->input('ids'));
        } else if($this->request->input('action') == 'save_add_genre') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $artist = Artist::find($id);
                if(isset($song->id)){
                    $currentGenre = explode(',', $song->genre);
                    $newGenre = array_unique(array_merge($currentGenre, $this->request->input('genre')));
                    $artist->genre = implode(',', $newGenre);
                    $artist->save();
                }
            }
            return redirect()->route('backend.artists')->with('status', 'success')->with('message', 'Artists successfully saved!');
        } elseif($this->request->input('action') == 'change_genre') {
            $message = 'Change genre';
            $subMessage = 'Change Genre for Chosen Artists (<strong>' . count($this->request->input('ids')) . '</strong>)';
            return view('backend.commons.mass_genre')
                ->with('message', $message)
                ->with('subMessage', $subMessage)
                ->with('action', $this->request->input('action'))
                ->with('ids', $this->request->input('ids'));
        } else if($this->request->input('action') == 'save_change_genre') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $artist = Artist::withoutGlobalScopes()->find($id);
                if(isset($song->id)){
                    $artist->genre = implode(',', $this->request->input('genre'));
                    $artist->save();
                }
            }
            return redirect()->route('backend.artists')->with('status', 'success')->with('message', 'Artists successfully saved!');
        } elseif($this->request->input('action') == 'add_mood') {
            $message = 'Add mood';
            $subMessage = 'Add Mood for Chosen Artists (<strong>' . count($this->request->input('ids')) . '</strong>)';
            return view('backend.commons.mass_mood')
                ->with('message', $message)
                ->with('subMessage', $subMessage)
                ->with('action', $this->request->input('action'))
                ->with('ids', $this->request->input('ids'));
        } else if($this->request->input('action') == 'save_add_mood') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $artist = Artist::withoutGlobalScopes()->find($id);
                if(isset($song->id)){
                    $currentMood = explode(',', $song->mood);
                    $newMood = array_unique(array_merge($currentMood, $this->request->input('mood')));
                    $artist->mood = implode(',', $newMood);
                    $artist->save();
                }
            }
            return redirect()->route('backend.artists')->with('status', 'success')->with('message', 'Artists successfully saved!');
        } elseif($this->request->input('action') == 'change_mood') {
            $message = 'Change mood';
            $subMessage = 'Change Mood for Chosen Artists (<strong>' . count($this->request->input('ids')) . '</strong>)';
            return view('backend.commons.mass_mood')
                ->with('message', $message)
                ->with('subMessage', $subMessage)
                ->with('action', $this->request->input('action'))
                ->with('ids', $this->request->input('ids'));
        } else if($this->request->input('action') == 'save_change_mood') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $artist = Artist::withoutGlobalScopes()->find($id);
                if(isset($song->id)){
                    $artist->mood = implode(',', $this->request->input('mood'));
                    $artist->save();
                }
            }
            return redirect()->route('backend.artists')->with('status', 'success')->with('message', 'Artists successfully saved!');
        } else if($this->request->input('action') == 'verified') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $artist = Artist::withoutGlobalScopes()->find($id);
                if(isset($artist->id)){
                    $artist->verified = 1;
                    $artist->save();
                }
            }
            return redirect()->route('backend.artists')->with('status', 'success')->with('message', 'Artists successfully saved!');
        } else if($this->request->input('action') == 'unverified') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $artist = Artist::withoutGlobalScopes()->find($id);
                if(isset($artist->id)){
                    $artist->verified = 0;
                    $artist->save();
                }
            }
            return redirect()->route('backend.artists')->with('status', 'success')->with('message', 'Artists successfully saved!');
        } else if($this->request->input('action') == 'comments') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $artist = Artist::withoutGlobalScopes()->find($id);
                if(isset($song->id)){
                    $song->allow_comments = 1;
                    $song->save();
                }
            }
            return redirect()->route('backend.artists')->with('status', 'success')->with('message', 'Artists successfully saved!');
        } else if($this->request->input('action') == 'not_comments') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $artist = Artist::withoutGlobalScopes()->find($id);
                if(isset($artist->id)){
                    $artist->allow_comments = 0;
                    $artist->save();
                }
            }
            return redirect()->route('backend.artists')->with('status', 'success')->with('message', 'Artists successfully saved!');
        } else if($this->request->input('action') == 'delete') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $artist = Artist::withoutGlobalScopes()->where('id', $id)->first();
                $artist->delete();
            }
            return redirect()->route('backend.artists')->with('status', 'success')->with('message', 'Artists successfully deleted!');
        }
    }
}