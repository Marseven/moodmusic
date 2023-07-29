<?php namespace App\Services\Artists;

use App\Artist;
use Illuminate\Pagination\AbstractPaginator;
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
    public function execute(Artist $artist, array $params): AbstractPaginator
    {
        $prefix = DB::getTablePrefix();
        $withTracks = castToBoolean(Arr::get($params, 'loadAlbumTracks', true));
        $perPage =
            (int) ($params['albumsPerPage'] ?? ($params['perPage'] ?? 5));

        $builder = $artist
            ->albums()
            ->with($withTracks ? ['artists', 'tracks.artists'] : ['artists'])
            ->leftjoin('tracks', 'tracks.album_id', '=', 'albums.id')
            ->groupBy('albums.id')
            // albums can have identical release dates, order by id to avoid duplicates in pagination
            ->orderByRaw(
                "COUNT({$prefix}tracks.id) > 5 desc, {$prefix}albums.release_date desc, {$prefix}albums.id desc",
            );

        if (Arr::get($params, 'paginate') === 'simple') {
            return $builder->simplePaginate($perPage);
        } else {
            return $builder->paginate($perPage);
        }
    }
}
