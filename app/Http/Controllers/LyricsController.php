<?php namespace App\Http\Controllers;

use App\Lyric;
use App\Services\Lyrics\ImportLyrics;
use App\Track;
use Common\Core\BaseController;
use Common\Database\Datasource\Datasource;
use Common\Settings\Settings;
use Illuminate\Http\Request;

class LyricsController extends BaseController
{
    public function __construct(
        protected Lyric $lyric,
        protected Track $track,
        protected Request $request,
    ) {
    }

    public function index()
    {
        $this->authorize('index', Lyric::class);

        $paginator = new Datasource($this->lyric, $this->request->all());
        return $this->success(['pagination' => $paginator->paginate()]);
    }

    public function show(int $trackId)
    {
        $this->authorize('show', Lyric::class);

        $lyric = $this->lyric->where('track_id', $trackId)->first();

        if (!$lyric && app(Settings::class)->get('player.lyrics_automate')) {
            $lyric = app(ImportLyrics::class)->execute($trackId);
        }

        if (!$lyric) {
            return $this->error(__('Could not find lyrics'), [], 404);
        }

        return $this->success(['lyric' => $lyric]);
    }

    public function store()
    {
        $this->authorize('store', Lyric::class);

        $this->validate($this->request, [
            'text' => 'required|string',
            'track_id' => 'required|integer|exists:tracks,id',
        ]);

        $lyric = $this->lyric->create([
            'track_id' => $this->request->get('track_id'),
            'text' => $this->request->get('text'),
        ]);

        return $this->success(['lyric' => $lyric]);
    }

    public function update(int $id)
    {
        $this->authorize('update', Lyric::class);

        $this->validate($this->request, [
            'text' => 'required|string',
            'track_id' => 'required|integer|exists:tracks,id',
        ]);

        $lyric = $this->lyric->findOrFail($id);

        $lyric->update([
            'track_id' => $this->request->get('track_id'),
            'text' => $this->request->get('text'),
        ]);

        return $this->success(['lyric' => $lyric]);
    }

    public function destroy(string $ids)
    {
        $lyricIds = explode(',', $ids);
        $this->authorize('destroy', [Lyric::class, $lyricIds]);

        $this->lyric->destroy($lyricIds);

        return $this->success();
    }
}
