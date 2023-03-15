<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-24
 * Time: 20:12
 */

namespace App\Http\Controllers\Backend;

use App\Jobs\ImportAlbum;
use App\Jobs\ImportArtist;
use App\Jobs\ImportSong;
use Illuminate\Http\Request;
use DB;
use View;
use Storage;
use Image;
use App\Modules\Spotify\Spotify;

class ImportController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(Request $request)
    {
        $view = View::make('backend.import.index');

        if($this->request->input('type') == 'song') {
            $data = (new Spotify)->searchTracks($this->request->input('term'))->get();
        } else if($this->request->input('type') == 'album') {
            $data = (new Spotify)->searchAlbums($this->request->input('term'))->get();
        } else if($this->request->input('type') == 'artist') {
            $data = (new Spotify)->searchArtists($this->request->input('term'))->get();
        }

        if(isset($data)) {
            $view->with('data', $data)->with('type', $this->request->input('type'));
        }

        return $view;
    }

    public function massAction()
    {
        $this->request->validate([
            'action' => 'required|string',
            'type' => 'required|string',
            'ids' => 'required|array',
        ]);

        if($this->request->input('action') == 'import') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                if($this->request->input('type') == 'artist') {
                    dispatch(new ImportArtist($id));
                } else if($this->request->input('type') == 'album') {
                    dispatch(new ImportAlbum($id));
                } else if($this->request->input('type') == 'song') {
                    dispatch(new ImportSong($id));
                }
            }
            return redirect()->route('backend.import')->with('status', 'success')->with('message', 'Successfully added to import queue!');
        }
    }
}