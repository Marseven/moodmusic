<?php

namespace App\Services\Playlists;

use App\Playlist;
use Common\Database\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginatePlaylists
{
    public function execute(array $params, Builder $builder = null): LengthAwarePaginator
    {
        $builder = $builder ?? Playlist::query();
        $paginator = (new Paginator($builder, $params))
            ->with(['editors' => function (BelongsToMany $q) {
                return $q->compact();
            }]);

        $order = $paginator->getOrder();
        if ($order['col'] === 'popularity') {
            $paginator->dontSort = true;
            $paginator->query()->orderByPopularity($order['dir']);
        }

        return $paginator->paginate();
    }
}
