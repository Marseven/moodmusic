<?php

namespace App\Services\Tracks;

use App\Genre;
use App\Track;
use Common\Database\Datasource\Datasource;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Str;

class PaginateTracks
{
    public function execute(array $params, Genre $genre = null): AbstractPaginator
    {
        if ($genre) {
            $builder = $genre->tracks();
        } else {
            $builder = Track::query();
        }

        $builder
            ->with('album')
            ->with('artists');

        $datasource = new Datasource($builder, $params);
        $order = $datasource->getOrder();

        if (Str::endsWith($order['col'], 'popularity')) {
            $datasource->order = false;
            $builder->orderByPopularity($order['dir']);
        }

        return $datasource->paginate();
    }
}
