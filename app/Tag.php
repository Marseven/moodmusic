<?php

namespace App;

use Common\Tags\Tag as BaseTag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Laravel\Scout\Searchable;

class Tag extends BaseTag
{
    use Searchable;

    protected $hidden = [
        'type',
        'created_at',
        'updated_at',
    ];

    /**
     * @return MorphToMany
     */
    public function tracks()
    {
        return $this->morphedByMany(Track::class, 'taggable');
    }

    /**
     * @return MorphToMany
     */
    public function albums()
    {
        return $this->morphedByMany(Album::class, 'taggable');
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'display_name' => $this->display_name,
        ];
    }

    public function basicSearch(string $query): Builder
    {
        return $this->where('name' ,'like', $query.'%');
    }
}
