<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-07-28
 * Time: 01:42
 */

namespace App\Models;

use App\Scopes\PublishedScope;
use App\Scopes\VisibilityScope;
use App\Traits\FullTextSearch;
use App\Traits\SanitizedRequest;
use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
    use InteractsWithMedia, FullTextSearch;

    protected $fillable = ['title', 'short_content', 'full_content'];

    protected $searchable = ['title', 'short_content', 'full_content'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new PublishedScope);
        static::addGlobalScope(new VisibilityScope);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->width(config('settings.image_max_thumbnail', 600))
            ->keepOriginalImageFormat()
            ->performOnCollections('artwork')
            ->nonOptimized()->nonQueued();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCategoriesAttribute()
    {
        return Category::whereIn('id', explode(',', $this->attributes['category']))->get();
    }

    public function getPermalinkUrlAttribute($value)
    {
        return route('frontend.post', ['id' => $this->attributes['id'], 'slug' => str_slug(html_entity_decode($this->attributes['title']))]);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function delete()
    {
        Comment::where('commentable_type', $this->getMorphClass())->where('commentable_id', $this->id)->delete();
        Notification::where('notificationable_type', $this->getMorphClass())->where('notificationable_id', $this->id)->delete();
        Activity::where('activityable_type', $this->getMorphClass())->where('activityable_id', $this->id)->delete();

        return parent::delete();
    }

    public function sanitizers(){
        die();
    }
}

