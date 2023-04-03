<?php

namespace Common\Comments;

use App\User;
use Common\Files\Traits\HandlesEntryPaths;
use Database\Factories\CommentFactory;
use Eloquent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Comment
 *
 * @property int $id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string content
 * @method Comment rootOnly()
 * @method Comment childrenOnly()
 * @mixin Eloquent
 */
class Comment extends Model
{
    use HandlesEntryPaths, HasFactory;

    protected $guarded = ['id'];

    protected $hidden = [
        'commentable_type',
        'commentable_id',
        'user_id',
        'path',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'deleted' => 'boolean'
    ];

    protected $appends = ['depth'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function commentable(): MorphTo {
        return $this->morphTo();
    }

    public function scopeRootOnly(Builder $builder)
    {
        return $builder->whereNull('parent_id');
    }

    public function scopeChildrenOnly(Builder $builder)
    {
        return $builder->whereNotNull('parent_id');
    }

    public function getDepthAttribute()
    {
        return substr_count($this->getRawOriginal('path'), '/');
    }

    protected static function newFactory()
    {
        return CommentFactory::new();
    }
}
