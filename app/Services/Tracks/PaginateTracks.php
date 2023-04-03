<?php

namespace App\Services\Tracks;

use App\Genre;
use App\Track;
use Common\Database\Paginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginateTracks
{
    public function execute(array $params, Genre $genre = null)
    {
        if ($genre) {
            $builder = $genre->tracks();
        } else {
            $builder = Track::query();
        }

        $paginator = (new Paginator($builder, $params))
            ->with('album')
            ->with('artists')
            ->withCount('plays');

        $order = $paginator->getOrder();
        if ($order['col'] === 'popularity') {
            $paginator->dontSort = true;
            $paginator->query()->orderByPopularity($order['dir']);
        }

        return $paginator->paginate();
    }
}
