<?php

namespace App\Services\Genres;

use App\Genre;
use Common\Database\Datasource\Datasource;
use Illuminate\Pagination\AbstractPaginator;

class PaginateGenres
{
    public function execute(array $params): AbstractPaginator
    {
        $datasource = new Datasource(Genre::query(), $params);

        return $datasource->paginate();
    }
}
