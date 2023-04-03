<?php namespace App\Http\Controllers;

use App\Album;
use App\Playlist;
use App\Services\Tracks\Queries\PlaylistTrackQuery;
use App\Track;
use Auth;
use Common\Database\Paginator;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\Playlists\PlaylistTracksPaginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Common\Core\BaseController;

class PlaylistTracksController extends BaseController {

    /**
     * @var Request
     */
    private $request;

    /**
     * @var PlaylistTracksPaginator
     */
    private $paginator;

    /**
     * @var Playlist
     */
    private $playlist;

    public function __construct(Request $request, PlaylistTracksPaginator $paginator, Playlist $playlist)
    {
        $this->request = $request;
        $this->paginator = $paginator;
        $this->playlist = $playlist;
    }

    public function index(int $playlistId) {

        $pagination = $this->paginator->paginate($playlistId);
        return $this->success(['pagination' => $pagination]);
    }

    public function add(int $id) {
        $playlist = $this->playlist->findOrFail($id);

        $this->authorize('modifyTracks', $playlist);

        $ids = collect($this->request->get('ids'))
            ->mapWithKeys(function($id, $index) {
                return [$id => ['position' => $index + 1, 'added_by' => Auth::id()]];
            });

        DB::table('playlist_user')->where('user_id', Auth::id())->update(['editor' => true]);

        DB::table('playlist_track')
            ->where('playlist_id', $playlist->id)
            ->increment('position', count($ids));

        $playlist->tracks()->sync($ids, false);
        $this->updateImage($playlist);

        return $this->success(['playlist' => $playlist]);
    }

    public function remove(int $id) {
        $playlist = $this->playlist->findOrFail($id);

        $this->authorize('modifyTracks', $playlist);

        $ids = $this->request->get('ids');
        $playlist->tracks()->detach($ids);
        $this->updateImage($playlist);

        return $this->success(['playlist' => $playlist]);
    }

    private function updateImage(Playlist $playlist)
    {
        if ( ! $playlist->image && $firstTrack = $playlist->tracks()->with('album')->first()) {
            if ($firstTrack->image) {
                $playlist->image = $firstTrack->image;
            } else if ($firstTrack->album) {
                $playlist->image = $firstTrack->album->image;
            }
            $playlist->save();
        }
    }
}
