<?php namespace App\Services\Artists;

use App\Artist;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PaginateArtistAlbums
{
    /**
     * Paginate all specified artist's albums.
     *
     * First order by number of tracks, so all albums
     * with less than 5 tracks (singles) are at
     * the bottom, then order by album release date.
     */
    public function execute(
        Artist $artist,
        array $params,
    ): LengthAwarePaginator {
        $prefix = DB::getTablePrefix();
        $withTracks = castToBoolean(Arr::get($params, 'loadAlbumTracks', true));

        if ($withTracks) {
            return $artist
                ->albums()
                ->with('artists', 'tracks.artists')
                ->leftjoin('tracks', 'tracks.album_id', '=', 'albums.id')
                ->groupBy('albums.id')
                ->orderByRaw(
                    "COUNT({$prefix}tracks.id) > 5 desc, {$prefix}albums.release_date desc",
                )
                ->paginate($params['albumsPerPage'] ?? 5);
        }

        return $artist
            ->albums()
            ->with('artists')
            ->orderBy('release_date')
            ->paginate($params['albumsPerPage'] ?? 5);
    }
}
