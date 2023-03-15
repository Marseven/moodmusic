<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 09:02
 */

namespace App\Http\Controllers\Backend;

use App\Models\Email;
use Illuminate\Http\Request;
use DB;
use View;
use Config;
use App\Models\Artist;
use App\Models\ArtistRequest;
use App\Models\Song;
use App\Models\Album;
use Auth;
use Image;

class ArtistAccessController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        isset($_GET['q']) ? $term = $_GET['q'] : $term = '';

        if($term) {
            $requests = ArtistRequest::withoutGlobalScopes()->where('name', 'like', '%' . $term . '%')->paginate(20);
        } else {
            $requests = ArtistRequest::withoutGlobalScopes()->paginate(20);
        }

        $total_requests = DB::table('artist_requests')->count();

        return view('backend.artist-access.index')
            ->with('requests', $requests)
            ->with('total_requests', $total_requests)
            ->with('term', $term);
    }

    public function add()
    {

        return view('backend.artists.add');
    }

    public function edit()
    {
        $request = ArtistRequest::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        return view('backend.artist-access.edit')
            ->with('request', $request);
    }

    public function editPost()
    {
        $this->request->validate([
            'reject' => 'nullable',
            'comment' => 'nullable|string',
        ]);

        $request = ArtistRequest::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        if($this->request->input('reject')) {
            $comment = $this->request->input('comment');

            try {
                (new Email)->rejectedArtist($request, $comment);
            } catch (\Exception $e) {

            }

            $request->delete();
            return redirect()->route('backend.artist.access')->with('status', 'success')->with('message', 'The artist has been rejected!');
        } else {
            $request->approved = 1;
            $request->save();

            if($request->artist) {
                $request->user->artist_id = $request->artist->id;
                $request->user->save();
                $request->artist->verified = 1;
                $request->artist->save();
                Song::where('artistIds', 'REGEXP', '(^|,)(' . $request->artist->id . ')(,|$)')->update(['user_id' => $request->user->id]);
                Album::where('artistIds', 'REGEXP', '(^|,)(' . $request->artist->id . ')(,|$)')->update(['user_id' => $request->user->id]);
            } else {
                $artist = new Artist();
                $artist->name = $request->artist_name;
                $artist->verified = 1;
                $artist->save();
                $request->user->artist_id = $artist->id;
                $request->user->save();
                $request->artist_id = $artist->id;
                $request->save();
            }

            try {
                (new Email)->approvedArtist($request);
            } catch (\Exception $e) {

            }

            return redirect()->route('backend.artist.access')->with('status', 'success')->with('message', 'Artist has been approved!');
        }

    }

    public function reject()
    {
        $request = ArtistRequest::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        try {
            (new Email)->rejectedArtist($request);
        } catch (\Exception $e) {

        }

        $request->delete();
        return redirect()->route('backend.artist.access')->with('status', 'success')->with('message', 'The artist has been rejected!');
    }
}