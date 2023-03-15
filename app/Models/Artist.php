<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-24
 * Time: 13:24
 */

namespace App\Models;

use App\Scopes\PublishedScope;
use App\Scopes\VisibilityScope;
use App\Traits\SanitizedRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use Carbon\Carbon;
use Spatie\Image\Manipulations;

class Artist extends Model implements HasMedia
{
    use InteractsWithMedia, SanitizedRequest;

    protected $fillable = ['genre', 'mood', 'title', 'artworkId', 'bio', 'name'];

    protected $appends = ['artwork_url', 'permalink_url'];

    protected $hidden = ['media', 'bio', 'visibility', 'created_at', 'updated_at'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new VisibilityScope);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('sm')
            ->fit(Manipulations::FIT_CROP, 60, 60)
            ->performOnCollections('artwork')->nonOptimized()->nonQueued();

        $this->addMediaConversion('md')
            ->fit(Manipulations::FIT_CROP, 120, 120)
            ->performOnCollections('artwork')->nonOptimized()->nonQueued();

        $this->addMediaConversion('lg')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->performOnCollections('artwork')->nonOptimized()->nonQueued();
    }

    public function getArtworkUrlAttribute($value)
    {
        $media = $this->getFirstMedia('artwork');
        if(! $media) {
            if(isset($this->log) && isset($this->log->artwork_url)) {
                return $this->log->artwork_url;
            } else {
                return route('frontend.artist.spotify.artwork', ['id' => $this->attributes['id']]);
            }
        } else {
            if ($media->disk == 's3') {
                return $media->getTemporaryUrl(Carbon::now()->addMinutes(intval(config('settings.s3_signed_time', 5))), 'lg');
            } else {
                return $media->getFullUrl('lg');
            }
        }
    }

    public function getMoodsAttribute()
    {
        return Mood::whereIn('id', explode(',', $this->attributes['mood']))->limit(4)->get();
    }

    public function getGenresAttribute($value)
    {
        return Genre::whereIn('id', explode(',', $this->attributes['genre']))->limit(4)->get();
    }

    public function getPermalinkUrlAttribute($value)
    {
        return route('frontend.artist', ['id' => $this->attributes['id'], 'slug' => str_slug($this->attributes['name']) ? str_slug(html_entity_decode($this->attributes['name'])) : str_replace(' ', '-', html_entity_decode($this->attributes['name']))]);
    }

    public function getSongCountAttribute($value)
    {
        if ($this->id < 1000) {
            return Song::where('artistIds', 'REGEXP', '(^|,)(' . $this->id . ')(,|$)')->count();
        } else {
            return Song::whereRaw("artistIds LIKE CONCAT('". $this->id . "', '%')")->count();
        }
    }

    public function getAlbumCountAttribute($value)
    {
        if ($this->id < 1000) {
            return Album::where('artistIds', 'REGEXP', '(^|,)(' . $this->id . ')(,|$)')->count();
        } else {
            return Album::whereRaw("artistIds LIKE CONCAT('". $this->id . "', '%')")->count();
        }
    }

    public function getLovedAttribute()
    {
        if(auth()->user()) {
            return $this->morphOne(Love::class, 'loveable')->where('user_id', auth()->user()->id) ? true : false;
        }
        return false;
    }

    public function getFavoriteAttribute($value) {
        if(auth()->check()){
            return Love::where('user_id', auth()->user()->id)->where('loveable_id', $this->id)->where('loveable_type', $this->getMorphClass())->exists();
        } else {
            return false;
        }
    }

    public function podcasts()
    {
        return $this->hasMany(Podcast::class, 'artist_id', 'id');
    }

    public function songs()
    {
        if ($this->id < 1000) {
            return Song::where('artistIds', 'REGEXP', '(^|,)(' . $this->id . ')(,|$)');
        } else {
            return Song::whereRaw("artistIds LIKE CONCAT('". $this->id . "', '%')");
        }
    }

    public function albums()
    {
        if ($this->id < 1000) {
            return Album::where('artistIds', 'REGEXP', '(^|,)(' . $this->id . ')(,|$)');
        } else {
            return Album::whereRaw("artistIds LIKE CONCAT('". $this->id . "', '%')");
        }
    }

    public function similar()
    {
        return Artist::whereIn('genre', explode(',', $this->genre));
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'love', 'loveable_id', 'user_id')->where('loveable_type', 'App\Models\Artist');
    }

    public function activities(){
        return Activity::where('activityable_type', 'App\Models\Artist')
            ->where('activityable_id', $this->id)
            ->where('action', '!=', 'addEvent');
    }

    public function events(){
        return $this->hasMany(Event::class);
    }

    public function log()
    {
        return $this->hasOne(ArtistLog::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function delete()
    {
        Comment::withoutGlobalScopes()->where('commentable_type', $this->getMorphClass())->where('commentable_id', $this->id)->delete();
        Love::withoutGlobalScopes()->where('loveable_type', $this->getMorphClass())->where('loveable_id', $this->id)->delete();
        User::withoutGlobalScopes()->where('artist_id', $this->id)->update(['artist_id' => null]);
        ArtistRequest::withoutGlobalScopes()->where('artist_id', $this->id)->delete();
        Activity::where('activityable_type', $this->getMorphClass())->where('activityable_id', $this->id)->delete();
        Podcast::withoutGlobalScopes()->where('artist_id', $this->id)->delete();
        ArtistLog::where('artist_id', $this->id)->delete();
        Popular::where('artist_id', $this->id)->delete();

        return parent::delete();
    }
}