<?php

namespace App\Services\Genres;

use App\Genre;
use Common\Database\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginateGenres
{
    public function execute(array $params): LengthAwarePaginator
    {
        $paginator = (new Paginator(Genre::query(), $params));

        return $paginator->paginate();
    }
}
