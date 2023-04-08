<?php

namespace App\Services\Albums;

use App\Album;
use App\Genre;
use Common\Database\Datasource\Datasource;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Str;

class PaginateAlbums
{
    public function execute(array $params, Genre $genre = null): AbstractPaginator
    {
        if ($genre) {
            $builder = $genre
                ->albums()
                ->whereNotNull('image');
        } else {
            $builder = Album::query();
        }

        $builder->with(['artists']);

        $datasource = (new Datasource($builder, $params));
        $order = $datasource->getOrder();


        if (Str::endsWith($order['col'], 'popularity')) {
            $datasource->order = false;
            $builder->orderByPopularity($order['dir']);
        }

        return $datasource->paginate();
    }
}
