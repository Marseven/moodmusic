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
use Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Module;

class Album extends Model implements HasMedia
{
    use InteractsWithMedia, SanitizedRequest;

    protected $appends = ['artwork_url', 'artists', 'song_count', 'permalink_url'];

    protected $hidden = ['media', 'user_id', 'artistIds', 'approved', 'updated_at'];

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
            if(isset($this->log) && isset($this->log->artwork_url)) {
                return $this->log->artwork_url;
            } else {
                return asset( 'common/default/album.png');
            }
        } else {
            if($media->disk == 's3') {
                return $media->getTemporaryUrl(Carbon::now()->addMinutes(intval(config('settings.s3_signed_time', 5))),'lg');
            } else {
                return $media->getFullUrl('lg');
            }
        }
    }

    public function getArtistsAttribute()
    {
        $idsArray = array_filter(explode(',', $this->attributes['artistIds']));
        $ids = implode(',', $idsArray);

        return Artist::whereIn('id', explode(',', $this->attributes['artistIds']))->orderBy(DB::raw('FIELD(id, ' .  $ids . ')', 'FIELD'))->get();
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
        return route('frontend.album', ['id' => $this->attributes['id'], 'slug' => str_slug(html_entity_decode($this->attributes['title'])) ? str_slug(html_entity_decode($this->attributes['title'])) : str_replace(' ', '-', html_entity_decode($this->attributes['title']))]);
    }

    public function getSongCountAttribute($value)
    {
        return AlbumSong::where('album_id', $this->id)->count();
    }

    public function getSalesAttribute()
    {
        return Order::groupBy('amount')->where('orderable_type', $this->getMorphClass())->where('orderable_id', $this->id)->count();
    }

    public function getPurchasedAttribute($value) {
        if(auth()->check() && $this->selling){
            return Order::where('user_id', auth()->user()->id)->where('orderable_id', $this->id)->where('orderable_type', $this->getMorphClass())->exists();
        } else {
            return false;
        }
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function songs()
    {
        return Song::leftJoin('album_songs', 'album_songs.song_id', '=', (new Song)->getTable() . '.id')
            ->select((new Song)->getTable() . '.*', 'album_songs.id as host_id')
            ->where('album_songs.album_id', $this->id)
            ->orderBy('album_songs.priority', 'asc')
            ->orderBy('album_songs.id', 'asc');
    }

    public function getFavoriteAttribute($value) {
        if(auth()->check()){
            return Love::where('user_id', auth()->user()->id)->where('loveable_id', $this->id)->where('loveable_type', $this->getMorphClass())->exists();
        } else {
            return false;
        }
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function love()
    {
        return $this->morphOne(Love::class, 'loveable')->where('user_id', auth()->user()->id);
    }

    public function log()
    {
        return $this->hasOne(AlbumLog::class);
    }

    private function checkFavorite($album_id)
    {
        if(auth()->check()){
            $row = DB::table('loves')
                ->select('loves.id')
                ->where('loves.user_id', auth()->user()->id)
                ->where('loves.item_id', $album_id)
                ->where('loves.type', 3)
                ->first();

            if((Object) $row && isset($row->id)) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    public function delete()
    {
        DB::table('album_songs')->where('album_id', $this->id)->delete();
        Comment::where('commentable_type', $this->getMorphClass())->where('commentable_id', $this->id)->delete();
        Love::where('loveable_type', $this->getMorphClass())->where('loveable_id', $this->id)->delete();
        Activity::where('activityable_type', $this->getMorphClass())->where('activityable_id', $this->id)->delete();
        AlbumLog::where('album_id', $this->id)->delete();
        AlbumSong::where('album_id', $this->id)->delete();
        Popular::where('album_id', $this->id)->delete();

        return parent::delete();
    }
}