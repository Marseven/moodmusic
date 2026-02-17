<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AdSpot extends Model
{
    const MODEL_TYPE = 'adSpot';

    protected $guarded = ['id'];

    protected $casts = [
        'id' => 'integer',
        'active' => 'boolean',
        'priority' => 'integer',
        'duration' => 'integer',
        'impressions' => 'integer',
        'clicks' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now()->toDateString());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now()->toDateString());
            });
    }

    public function scopeBanner(Builder $query): Builder
    {
        return $query->where('type', 'banner');
    }

    public function scopeAudio(Builder $query): Builder
    {
        return $query->where('type', 'audio');
    }
}
