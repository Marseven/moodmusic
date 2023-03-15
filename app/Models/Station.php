<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 08:47
 */

namespace App\Models;

use App\Traits\SanitizedRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Traits\FullTextSearch;

class Station extends Model implements HasMedia
{
    use InteractsWithMedia, FullTextSearch, SanitizedRequest;

    protected $appends = ['artwork_url', 'permalink_url'];

    protected $hidden = ['media'];

    protected $searchable = ['title', 'description'];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('sm')
            ->width(60)
            ->height(60)
            ->performOnCollections('artwork')->nonOptimized()->nonQueued();

        $this->addMediaConversion('md')
            ->width(120)
            ->height(120)
            ->performOnCollections('artwork')->nonOptimized()->nonQueued();

        $this->addMediaConversion('lg')
            ->width(300)
            ->height(300)
            ->performOnCollections('artwork')->nonOptimized()->nonQueued();
    }

    public function getArtworkUrlAttribute($value)
    {
        $media = $this->getFirstMedia('artwork');
        if(! $media) {
            return asset( 'common/default/station.png');
        } else {
            if($media->disk == 's3') {
                return $media->getTemporaryUrl(Carbon::now()->addMinutes(intval(config('settings.s3_signed_time', 5))),'lg');
            } else {
                return $media->getFullUrl('lg');
            }
        }
    }

    public function getCategoriesAttribute()
    {
        return Radio::whereIn('id', explode(',', $this->attributes['category']))->get();
    }

    public function getPermalinkUrlAttribute($value)
    {
        return route('frontend.station', ['id' => $this->attributes['id'], 'slug' => str_slug(html_entity_decode($this->attributes['title'])) ? str_slug(html_entity_decode($this->attributes['title'])) : str_replace(' ', '-', html_entity_decode($this->attributes['title']))]);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function delete()
    {
        Comment::where('commentable_type', $this->getMorphClass())->where('commentable_id', $this->id)->delete();
        Love::where('loveable_type', $this->getMorphClass())->where('loveable_id', $this->id)->delete();
        Notification::where('notificationable_type', $this->getMorphClass())->where('notificationable_id', $this->id)->delete();
        Activity::where('activityable_type', $this->getMorphClass())->where('activityable_id', $this->id)->delete();

        return parent::delete();
    }
}
