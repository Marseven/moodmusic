<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vibe extends Model
{
    const MODEL_TYPE = 'vibe';

    protected $guarded = ['id'];

    protected $casts = [
        'id' => 'integer',
        'genre_id' => 'integer',
        'channel_id' => 'integer',
        'position' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['model_type'];

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public static function getModelTypeAttribute(): string
    {
        return self::MODEL_TYPE;
    }
}
