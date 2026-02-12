<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RadioStation extends Model
{
    const MODEL_TYPE = 'radioStation';

    protected $guarded = ['id'];

    protected $casts = [
        'id' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'listeners_count' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
