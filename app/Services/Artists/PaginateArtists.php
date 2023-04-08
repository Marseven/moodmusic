<?php

namespace App\Services\Artists;

use App\Artist;
use App\Genre;
use Common\Database\Datasource\Datasource;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Str;

class PaginateArtists
{
    public function execute(
        array $params,
        Genre $genre = null
    ): AbstractPaginator {
        if ($genre) {
            $builder = $genre->artists()->whereNotNull("image_small");
        } else {
            $builder = Artist::query();
        }

        $builder->withCount(["albums"]);

        $datasource = new Datasource($builder, $params);
        $order = $datasource->getOrder();

        if (Str::endsWith($order['col'], 'popularity')) {
            $datasource->order = false;
            $builder->orderByPopularity($order["dir"]);
        }

        return $datasource->paginate();
    }
}
