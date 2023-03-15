<?php
/**
 * Created by PhpStorm.
 * User: lechchut
 * Date: 7/23/19
 * Time: 12:19 PM
 */

namespace App\Models;

use App\Traits\SanitizedRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    use InteractsWithMedia, SanitizedRequest;

    protected $fillable = ['parent_id' ,'posi' ,'name' ,'alt_name' ,'description' ,'news_sort' ,'news_msort' ,'news_number' ,'meta_title', 'meta_keywords', 'meta_description' ,'show_sub' ,'allow_rss' ,'disable_search' ,'disable_main' ,'disable_comments' ,'artworkId'];

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
        return route('frontend.blog.category', ['category' => $this->attributes['alt_name']]);
    }
}