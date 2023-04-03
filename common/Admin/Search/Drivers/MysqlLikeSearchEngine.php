<?php namespace Common\Admin\Search\Drivers;

use Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Laravel\Scout\Builder;
use Laravel\Scout\Engines\Engine;

class MysqlLikeSearchEngine extends Engine
{
    /**
     * Update the given model in the index.
     *
     * @param \Illuminate\Database\Eloquent\Collection $models
     * @return void
     */
    public function update($models)
    {
        //
    }

    /**
     * Remove the given model from the index.
     *
     * @param \Illuminate\Database\Eloquent\Collection $models
     * @return void
     */
    public function delete($models)
    {
        //
    }

    public function search(Builder $builder)
    {
        return $this->performSearch($builder, ['perPage' => $builder->limit]);
    }

    public function paginate(Builder $builder, $perPage, $page): Collection
    {
        return $this->performSearch($builder, ['perPage' => $perPage, 'page' => $page])
            ->skip($page * $perPage - $perPage)->take($perPage);
    }

    protected function performSearch(Builder $builder, array $options = []): Collection
    {
        if ($builder->callback) {
            return call_user_func($builder->callback, null, $builder->query, $options);
        }

        $perPage = $options['perPage'] ?? 20;
        $page = $options['page'] ?? 1;

        $query = $builder->model->basicSearch($builder->query)
            ->skip($perPage * ($page - 1))->take($perPage);

        if (!empty($builder->orders)) {
            foreach ($builder->orders as $order) {
                $query->orderBy(Arr::get($order, 'column'), Arr::get($order, 'direction'));
            }
        }

        return $query->get();
    }

    /**
     * Pluck and return the primary keys of the given results.
     *
     * @param mixed $results
     * @return Collection
     */
    public function mapIds($results)
    {
        return $results->pluck('id')->values();
    }

    /**
     * Map the given results to instances of the given model.
     *
     * @param Builder $builder
     * @param mixed $results
     * @param Model $model
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function map(Builder $builder, $results, $model)
    {
        return $results;
    }

    /**
     * Get the total count from a raw result returned by the engine.
     *
     * @param mixed $results
     * @return int
     */
    public function getTotalCount($results)
    {
        return count($results);
    }

    /**
     * @inheritDoc
     */
    public function flush($model)
    {
        //
    }
}
