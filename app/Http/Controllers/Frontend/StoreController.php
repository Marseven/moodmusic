<?php


namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use DB;
use View;
use App\Models\Genre;
use App\Models\Mood;
use App\Models\Artist;
use App\Models\Song;

class StoreController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

    }

    public function index()
    {
        $songs = Song::where('selling', 1);

        if ($this->request->input('genres') && is_array($this->request->input('genres')))
        {
            $songs = $songs->where('genre', 'REGEXP', '(^|,)(' . implode(',', $this->request->input('genres')) . ')(,|$)');
        }

        if ($this->request->input('moods') && is_array($this->request->input('moods')))
        {
            $songs = $songs->where('mood', 'REGEXP', '(^|,)(' . implode(',', $this->request->input('moods')) . ')(,|$)');
        }


        if ($this->request->has('terms'))
        {
            $songs = $songs->where(function ($query) {
                foreach($this->request->input('terms') as $index => $term) {
                    if($index == 0) {
                        $query->where('title', 'like', '%' . $term . '%');
                    } else {
                        $query->orWhere('title', 'like', '%' . $term . '%');
                    }
                }
            });
        }

        if ($this->request->input('artists') && is_array($this->request->input('artists')))
        {
            $songs = $songs->where(function ($query) {
                foreach($this->request->input('artists') as $index => $artistId) {
                    if($index == 0) {
                        $query->where('artistIds', 'REGEXP', '(^|,)(' . $artistId . ')(,|$)');
                    } else {
                        $query->orWhere('artistIds', 'REGEXP', '(^|,)(' . $artistId . ')(,|$)');
                    }
                }
            });
        }

        $songs = $songs->paginate(50);

        $view = View::make('store.index')->with('songs', $songs);

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            if(($this->request->input('page') && intval($this->request->input('page')) > 1) || $this->request->input('browsing'))
            {
                return $sections['pagination'];
            } else {
                return $sections['content'];
            }
        }
        getMetatags();

        return $view;
    }

    public function add (){

    }

    public function filter (){

    }

    public function genres (){
        return response()->json(Genre::orderBy('discover', 'desc')->limit(100)->get());
    }

    public function moods (){
        return response()->json(Mood::all());
    }

    public function artists (){
        return response()->json(Artist::limit(100)->latest()->get());
    }
}