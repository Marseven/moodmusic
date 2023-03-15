<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 08:54
 */

namespace App\Models;

use App\Scopes\VisibilityScope;
use App\Traits\SanitizedRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use DB;
use Auth;

class Playlist extends Model implements HasMedia
{
    use InteractsWithMedia, SanitizedRequest;

    protected $appends = ['artwork_url', 'permalink_url', 'song_count', 'subscriber_count', 'favorite'];

    protected $hidden = ['media', 'genre', 'user_id', 'mood', 'approved', 'updated_at'];

    protected $with = ['user'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new VisibilityScope);
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
                return asset( 'common/default/playlist.png');
            }
        } else {
            if($media->disk == 's3') {
                return $media->getTemporaryUrl(Carbon::now()->addMinutes(intval(config('settings.s3_signed_time', 5))),'lg');
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
        return route('frontend.playlist', ['id' => $this->attributes['id'], 'slug' => str_slug(html_entity_decode($this->attributes['title'])) ? str_slug(html_entity_decode($this->attributes['title'])) : str_replace(' ', '-', html_entity_decode($this->attributes['title']))]);
    }

    public function songs()
    {
        return Song::leftJoin('playlist_songs', 'playlist_songs.song_id', '=', (new Song)->getTable() . '.id')
            ->select('songs.*', 'playlist_songs.id as host_id')
            ->where('playlist_songs.playlist_id', $this->id)
            ->orderBy('playlist_songs.priority', 'asc');
    }

    public function getSongCountAttribute($value)
    {
        return $this->songs()->count();
    }

    public function getFavoriteAttribute($value) {
        if(auth()->check()){
            return Love::where('user_id', auth()->user()->id)->where('loveable_id', $this->id)->where('loveable_type', $this->getMorphClass())->exists();
        } else {
            return false;
        }
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'activityable')->with('user')->latest();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function subscribers(){

        return $this->belongsToMany(User::class, 'love', 'loveable_id', 'user_id')->where('loveable_type', 'App\Models\Playlist');
    }

    public function getSubscriberCountAttribute($value)
    {
        return $this->subscribers()->count();
    }

    public function log()
    {
        return $this->hasOne(PlaylistLog::class);
    }

    public function collaborators(){
        return User::leftJoin('collaborators', 'users.id', '=', 'collaborators.friend_id')
            ->select('users.*', 'collaborators.id as host_id')
            ->where('collaborators.playlist_id', $this->id)
            ->where('collaborators.approved', 1);
    }

    public function delete()
    {
        DB::table('playlist_songs')->where('playlist_id', $this->id)->delete();
        DB::table('collaborators')->where('playlist_id', $this->id)->delete();
        Comment::where('commentable_type', $this->getMorphClass())->where('commentable_id', $this->id)->delete();
        Love::where('loveable_type', $this->getMorphClass())->where('loveable_id', $this->id)->delete();
        Notification::where('notificationable_type', $this->getMorphClass())->where('notificationable_id', $this->id)->delete();
        Activity::where('activityable_type', $this->getMorphClass())->where('activityable_id', $this->id)->delete();
        PlaylistLog::where('playlist_id', $this->id)->delete();
        PlaylistSong::where('playlist_id', $this->id)->delete();
        Popular::where('playlist_id', $this->id)->delete();

        return parent::delete();
    }
}
