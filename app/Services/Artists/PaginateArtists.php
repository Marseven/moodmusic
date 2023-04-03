<?php

namespace App\Services\Artists;

use App\Artist;
use App\Genre;
use Common\Database\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Str;

class PaginateArtists
{
    public function execute(array $params, Genre $genre = null): LengthAwarePaginator
    {
        if ($genre) {
            $builder = $genre
                ->artists()
                ->whereNotNull('image_small')
                ->groupBy('name');
        } else {
            $builder = Artist::query();
        }

        $paginator = (new Paginator($builder, $params))
            ->withCount('albums');

        $order = $paginator->getOrder();
        if ($order['col'] === 'popularity') {
            $paginator->dontSort = true;
            $paginator->query()->orderByPopularity($order['dir']);
        }

        return $paginator->paginate();
    }
}
