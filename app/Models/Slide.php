<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 21:10
 */

namespace App\Models;

use App\Scopes\PriorityScope;
use App\Traits\SanitizedRequest;
use Carbon\Carbon;
use DB;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Slide extends Model implements HasMedia
{
    use InteractsWithMedia, SanitizedRequest;

    protected $appends = [
        'artwork_url', 'object'
    ];

    protected $hidden = [
        'media',
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new PriorityScope);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('sm')
            ->width(60)
            ->performOnCollections('artwork')->nonOptimized()->nonQueued();

        $this->addMediaConversion('md')
            ->width(120)
            ->performOnCollections('artwork')->nonOptimized()->nonQueued();

        $this->addMediaConversion('lg')
            ->width(500)
            ->performOnCollections('artwork')->nonOptimized()->nonQueued();
    }

    public function getArtworkUrlAttribute($value)
    {
        $media = $this->getFirstMedia('artwork');
        if(! $media) {
            return asset( 'common/default/song.png');
        } else {
            if($media->disk == 's3') {
                return $media->getTemporaryUrl(Carbon::now()->addMinutes(intval(config('settings.s3_signed_time', 5))),'lg');
            } else {
                return $media->getFullUrl('lg');
            }
        }
    }

    public function getObjectAttribute($value)
    {
        if ($this->attributes['object_type'] == "artist") {
            return Artist::find($this->attributes['object_id']);
        } elseif ($this->attributes['object_type'] == "song") {
            return Song::with('user')->find( $this->attributes['object_id']);
        } elseif ($this->attributes['object_type'] == "playlist") {
            return Playlist::with('user')->find($this->attributes['object_id']);
        } elseif ($this->attributes['object_type'] == "album") {
            return Album::find( $this->attributes['object_id']);
        } elseif ($this->attributes['object_type'] == "station") {
            return Station::find($this->attributes['object_id']);
        } elseif ($this->attributes['object_type'] == "user") {
            return User::find($this->attributes['object_id']);
        } elseif ($this->attributes['object_type'] == "podcast") {
            return Podcast::with('artist')->find($this->attributes['object_id']);
        } elseif ($this->attributes['object_type'] == "video") {
            return \App\Modules\Video\Video::with('user')->find($this->attributes['object_id']);
        }
    }

}