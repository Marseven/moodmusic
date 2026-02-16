<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OriginalContentCategory extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'position' => 'integer',
    ];

    public function tracks(): HasMany
    {
        return $this->hasMany(Track::class, 'original_content_category_id');
    }
}
