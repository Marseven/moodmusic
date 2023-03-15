<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-24
 * Time: 13:24
 */

namespace App\Models;

use App\Traits\SanitizedRequest;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ArtistRequest extends Model implements HasMedia
{
    use InteractsWithMedia, SanitizedRequest;

    protected $table = 'artist_requests';

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('lg')
            ->width(1024)
            ->performOnCollections('artwork')->nonOptimized()->nonQueued();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function artist()
    {
        return $this->hasOne(Artist::class, 'id', 'artist_id');
    }
}