<?php

namespace App\Services\Albums;

use App\Album;
use App\Genre;
use Common\Database\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginateAlbums
{
    public function execute(array $params, Genre $genre = null): LengthAwarePaginator
    {
        if ($genre) {
            $builder = $genre
                ->albums()
                ->whereNotNull('image');
        } else {
            $builder = Album::query();
        }

        $paginator = (new Paginator($builder, $params))
            ->with('artists');

        $order = $paginator->getOrder();
        if ($order['col'] === 'popularity') {
            $paginator->dontSort = true;
            $paginator->query()->orderByPopularity($order['dir']);
        }

        return $paginator->paginate();
    }
}
