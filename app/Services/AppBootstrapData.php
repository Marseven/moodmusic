<?php namespace App\Services;

use App\Artist;
use Common\Core\Bootstrap\BaseBootstrapData;
use DB;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class AppBootstrapData extends BaseBootstrapData
{
    public function init(): self
    {
        parent::init();

        if (isset($this->data['user'])) {
            $this->getUserLikes();
            $this->loadUserPlaylists();
            $this->loadUserFollowedUsers();
            $this->loadManagedArtists();
            $this->loadUserReposts();
        }

        $this->data['settings']['spotify_is_setup'] =
            config('common.site.spotify.id') &&
            config('common.site.spotify.secret');
        $this->data['settings']['lastfm_is_setup'] = !!config(
            'common.site.lastfm.key',
        );

        return $this;
    }

    /**
     * Load users that current user is following.
     */
    private function loadUserFollowedUsers()
    {
        $this->data['user'] = $this->data['user']->load([
            'followedUsers' => function (BelongsToMany $q) {
                return $q->select('users.id', 'users.avatar');
            },
        ]);
    }

    /**
     * Get ids of all tracks in current user's library.
     */
    private function getUserLikes()
    {
        $this->data['likes'] = DB::table('likes')
            ->where('user_id', $this->data['user']['id'])
            ->get(['likeable_id', 'likeable_type'])
            ->groupBy(function ($likeable) {
                return $likeable->likeable_type::MODEL_TYPE;
            })
            ->map(function (Collection $likeableGroup) {
                return $likeableGroup->mapWithKeys(function ($likeable) {
                    return [$likeable->likeable_id => true];
                });
            });
    }

    private function loadUserPlaylists()
    {
        $this->data['playlists'] = $this->data['user']
            ->playlists()
            ->compact()
            ->limit(30)
            ->orderBy('playlists.updated_at', 'desc')
            ->get()
            ->toArray();
    }

    private function loadManagedArtists()
    {
        $this->data['user']['artists'] = $this->data['user']
            ->artists()
            ->get(['artists.id', 'name', 'image_small'])
            ->map(function (Artist $artist) {
                $normalizedModel = $artist->toNormalizedArray();
                $normalizedModel['role'] = $artist->pivot->role;
                return $normalizedModel;
            });
    }

    private function loadUserReposts()
    {
        $this->data['reposts'] = DB::table('reposts')
            ->where('user_id', $this->data['user']['id'])
            ->get(['repostable_id', 'repostable_type'])
            ->groupBy(fn($item) => $item->repostable_type::MODEL_TYPE)
            ->map(function (Collection $likeableGroup) {
                return $likeableGroup->mapWithKeys(
                    fn($item) => [$item->repostable_id => true],
                );
            });
    }
}
