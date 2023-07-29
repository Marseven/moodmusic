<?php namespace App\Http\Controllers\Search;

use App\Services\Providers\ProviderResolver;
use App\Track;
use Common\Core\BaseController;
use Common\Settings\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class SearchController extends BaseController
{
    public function __construct(
        protected Request $request,
        protected Settings $settings,
        protected ProviderResolver $provider,
    ) {
    }

    public function index()
    {
        $modelTypes = explode(',', $this->request->get('types'));

        $limit = $this->request->get('limit', 3);
        $query = $this->request->get('query');
        $contentProvider = $this->provider->get(
            'search',
            request('localOnly') ? 'local' : null,
        );
        $response = [
            'query' => e($query),
            'results' => [],
        ];

        if ($query) {
            $modelTypes = array_filter($modelTypes, function ($modelType) {
                return Gate::inspect(
                    'index',
                    modelTypeToNamespace($modelType),
                )->allowed();
            });

            $results = $contentProvider->search($query, $limit, $modelTypes);

            if ($this->request->get('normalize')) {
                $results = $results->mapWithKeys(function (
                    $models,
                    $modelName,
                ) {
                    return [
                        $modelName => $models->map(
                            fn($model) => $model->toNormalizedArray(),
                        ),
                    ];
                });
            }

            $response['results'] = $results;

            if ($this->request->get('flatten')) {
                $response['results'] = Arr::flatten($response['results'], 1);
            }
        }

        return $this->success($response);
    }

    public function searchAudio(
        int $trackId,
        string $artistName,
        string $trackName,
    ) {
        $this->authorize('index', Track::class);

        $results = $this->provider
            ->get('audio_search')
            ->search($trackId, $artistName, $trackName, 1);

        return $this->success(['results' => $results]);
    }

    /**
     * Remove artists that were blocked by admin from search results.
     */
    private function filterOutBlockedArtists(array $results): array
    {
        if ($artists = $this->settings->get('artists.blocked')) {
            $artists = json_decode($artists);

            if (isset($results['artists'])) {
                foreach ($results['artists'] as $k => $artist) {
                    if ($this->shouldBeBlocked($artist['name'], $artists)) {
                        unset($results['artists'][$k]);
                    }
                }
            }

            if (isset($results['albums'])) {
                foreach ($results['albums'] as $k => $album) {
                    if (isset($album['artists'])) {
                        if (
                            $this->shouldBeBlocked(
                                $album['artists'][0]['name'],
                                $artists,
                            )
                        ) {
                            unset($results['albums'][$k]);
                        }
                    }
                }
            }

            if (isset($results['tracks'])) {
                foreach ($results['tracks'] as $k => $track) {
                    if (isset($track['album']['artists'])) {
                        if (
                            $this->shouldBeBlocked(
                                $track['album']['artists'][0]['name'],
                                $artists,
                            )
                        ) {
                            unset($results['tracks'][$k]);
                        }
                    }
                }
            }
        }

        return $results;
    }

    /**
     * Check if given artist should be blocked.
     */
    private function shouldBeBlocked(string $name, array $toBlock): bool
    {
        foreach ($toBlock as $blockedName) {
            $pattern =
                '/' . str_replace('*', '.*?', strtolower($blockedName)) . '/i';
            if (preg_match($pattern, $name)) {
                return true;
            }
        }
    }
}
