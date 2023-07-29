<?php namespace App\Http\Controllers;

use App;
use App\Actions\Track\DeleteTracks;
use App\Artist;
use App\Genre;
use App\Http\Requests\ModifyTracks;
use App\Services\Tracks\CrupdateTrack;
use App\Services\Tracks\PaginateTracks;
use App\Track;
use Arr;
use Common\Core\BaseController;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class TrackController extends BaseController
{
    public function __construct(
        protected Track $track,
        protected Request $request,
    ) {
    }

    public function index()
    {
        $this->authorize('index', Track::class);

        $pagination = App(PaginateTracks::class)->execute(
            $this->request->all(),
        );

        $pagination->makeVisible(['views', 'updated_at', 'plays']);

        return $this->success(['pagination' => $pagination]);
    }

    public function show(Track $track)
    {
        $this->authorize('show', $track);

        $params = $this->request->all();
        if (
            $this->request->get('defaultRelations') ||
            defined('SHOULD_PRERENDER')
        ) {
            $load = ['tags', 'genres', 'artists', 'fullAlbum'];
            $loadCount = ['reposts', 'likes'];
        } else {
            $load = array_filter(explode(',', Arr::get($params, 'with', '')));
            $loadCount = array_filter(
                explode(',', Arr::get($params, 'withCount', '')),
            );
        }

        foreach ($load as $relation) {
            if ($relation === 'fullAlbum') {
                $track->load([
                    'album' => function (BelongsTo $builder) {
                        return $builder->with(['artists', 'tracks.artists']);
                    },
                ]);
            } else {
                $track->load(trim($relation));
            }
        }

        $track->loadCount($loadCount);

        if ($track->relationLoaded('album') && $track->album) {
            $track->album->addPopularityToTracks();
        }

        $track->makeVisible('description');

        if (Arr::get($params, 'forEditing')) {
            $track->setHidden([]);
            $track->setRelation(
                'artists',
                $track->artists->map(
                    fn(Artist $artist) => $artist->toNormalizedArray(),
                ),
            );
            $track->setRelation(
                'genres',
                $track->genres->map(
                    fn(Genre $genre) => $genre->toNormalizedArray(),
                ),
            );
        }

        return $this->success(['track' => $track]);
    }

    public function store(ModifyTracks $request)
    {
        $this->authorize('store', Track::class);

        $track = app(CrupdateTrack::class)->execute(
            $request->all(),
            null,
            $request->get('album'),
        );

        return $this->success(['track' => $track]);
    }

    public function update(int $id, ModifyTracks $request)
    {
        $track = $this->track->findOrFail($id);

        $this->authorize('update', $track);

        $track = app(CrupdateTrack::class)->execute(
            $request->all(),
            $track,
            $request->get('album'),
        );

        return $this->success(['track' => $track]);
    }

    public function destroy(string $ids)
    {
        $trackIds = explode(',', $ids);
        $this->authorize('destroy', [Track::class, $trackIds]);

        app(DeleteTracks::class)->execute($trackIds);

        return $this->success();
    }
}
