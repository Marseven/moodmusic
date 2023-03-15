<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 08:47
 */

namespace App\Models;

use App\Scopes\ApprovedScope;
use App\Scopes\PublishedScope;
use App\Scopes\VisibilityScope;
use App\Traits\SanitizedRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\URL;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Traits\FullTextSearch;

class Episode extends Model implements HasMedia
{
    use InteractsWithMedia, FullTextSearch, SanitizedRequest;

    protected $appends = ['permalink_url', 'streamable'];

    protected $hidden = ['media', 'user'];

    protected $searchable = ['title', 'description'];

    protected static function booted()
    {
        self::creating(function ($model) {
            if(auth()->check()) {
                $model->user_id = auth()->user()->id;
            }
        });
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new VisibilityScope);
        static::addGlobalScope(new ApprovedScope);
        static::addGlobalScope(new PublishedScope);
    }

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
            return $this->podcast->artwork_url;
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
        return route('frontend.podcast.episode', ['id' => $this->podcast->id, 'slug' => str_slug(html_entity_decode($this->podcast->title)), 'epid' => $this->attributes['id']]);
    }

    public function getFavoriteAttribute($value) {
        if(auth()->check()){
            return Love::where('user_id', auth()->user()->id)->where('loveable_id', $this->id)->where('loveable_type', $this->getMorphClass())->exists();
        } else {
            return false;
        }
    }

    public function getStreamAbleAttribute($value)
    {
        if(isset($this->attributes['access'])) {
            $options = groupPermission($this->attributes['access']);

            if($this->attributes['access'] && isset($options[Role::groupId()])) {
                $permission = $options[Role::groupId()];
                switch ($permission) {
                    case 1:
                        return true;
                        break;
                    case 2:
                        return true;
                        break;
                    case 3:
                        return false;
                        break;
                }
            }
        }

        return Role::getValue('option_stream') ? true : false;
    }

    public function getStreamUrlAttribute($value)
    {
        if(isset($this->attributes['stream_url']) && $this->attributes['stream_url']) {
            return $this->attributes['stream_url'];
        } else {
            if (isset($this->attributes['hls']) && $this->attributes['hls']) {
                return route('frontend.podcast.episode.stream.hls', ['id' => $this->attributes['id']]);
            } else {
                return URL::temporarySignedRoute('frontend.podcast.episode.stream.mp3', now()->addDay(), [
                    'id' => $this->attributes['id']
                ]);
            }
        }
    }

    public function podcast(){
        return $this->belongsTo(Podcast::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function delete()
    {
        Comment::where('commentable_type', $this->getMorphClass())->where('commentable_id', $this->id)->delete();
        Love::where('loveable_type', $this->getMorphClass())->where('loveable_id', $this->id)->delete();
        Notification::where('notificationable_type', $this->getMorphClass())->where('notificationable_id', $this->id)->delete();
        Activity::where('activityable_type', $this->getMorphClass())->where('activityable_id', $this->id)->delete();
        Report::where('reportable_type', $this->getMorphClass())->where('reportable_id', $this->id)->delete();

        return parent::delete();
    }
}