<?php namespace App\Services\Artists;

use App\Artist;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaginateArtistTracks
{
    public function execute(Artist $artist): LengthAwarePaginator
    {
        return $artist
            ->tracks()
            ->with('genres')
            ->withCount(['plays', 'likes', 'reposts'])
            ->paginate(request('perPage') ?? 20);
    }
}
