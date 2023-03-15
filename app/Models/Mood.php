<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-07-06
 * Time: 22:25
 */

namespace App\Models;

use App\Scopes\PriorityScope;
use App\Traits\SanitizedRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Mood extends Model implements HasMedia
{
    use InteractsWithMedia, SanitizedRequest;

    protected $appends = ['artwork_url', 'permalink_url'];

    protected $hidden = ['media', 'created_at', 'description', 'meta_description', 'meta_keywords', 'meta_title', 'updated_at'];

    protected $fillable = ['parent_id' ,'posi' ,'name' ,'alt_name' ,'description' ,'meta_title' ,'meta_description', 'meta_keywords'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new PriorityScope);
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
            return asset( 'common/default/song.png');
        } else {
            if($media->disk == 's3') {
                return $media->getTemporaryUrl(Carbon::now()->addMinutes(intval(config('settings.s3_signed_time', 5))),'lg');
            } else {
                return $media->getFullUrl('lg');
            }
        }
    }

    public function getPermalinkUrlAttribute($value)
    {
        return route('frontend.mood', ['slug' => $this->attributes['alt_name']]);
    }

}