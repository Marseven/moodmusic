<?php

namespace App\Models;

use App\Traits\SanitizedRequest;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Collection;
use DB;
use Auth;
use Carbon\Carbon;
use phpDocumentor\Reflection\Types\Boolean;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, Notifiable, InteractsWithMedia, SanitizedRequest;

    protected $fillable = [
        'name', 'email', 'password', 'username', 'artworkId', 'email_verified_code'
    ];

    protected $appends = [
        'artwork_url', 'permalink_url', 'favorite'
    ];

    protected $hidden = [
        'media', 'banned', 'location', 'email', 'password', 'remember_token', 'balance', 'credit', 'email_verified_at', 'last_seen_notif',
        'logged_ip', 'gender', 'birth', 'birthyear', 'city', 'country', 'activity_privacy', 'created_at', 'updated_at',
        'restore_queue', 'persist_shuffle', 'play_pause_fade', 'disablePlayerShortcuts', 'crossfade_amount', 'notif_follower',
        'notif_playlist', 'notif_shares', 'notif_features', 'email_verified', 'email_verified_code',
        'playlist_count', 'favorite_count', 'hd_streaming', 'can_stream_high_quality', 'can_upload', 'trialed', 'payment_method', 'payment_paypal', 'payment_bank'
    ];

    protected static function booted()
    {
        static::created(function ($user) {
            RoleUser::updateOrCreate([
                'user_id' => $user->id,
            ], [
                'role_id' => config('settings.default_usergroup', 5),
            ]);
        });

        self::creating(function ($model) {
            $model->last_seen_notif = Carbon::now();
        });
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
            return asset( 'common/default/user.png');
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
        return route('frontend.user', ['username' => str_slug($this->attributes['username'])]);
    }

    public function getFavoriteAttribute($value) {
        if(auth()->check()){
            return Love::where('user_id', auth()->user()->id)->where('loveable_id', $this->id)->where('loveable_type', $this->getMorphClass())->exists();
        } else {
            return false;
        }
    }

    public function getCanStreamHighQualityAttribute($value) {
        return $this->attributes['hd_streaming'] ? (boolean) Role::getValue('option_hd_stream') : false;
    }

    public function getFollowerCountAttribute($value) {
        return $this->followers()->count();
    }

    public function getFollowingCountAttribute($value) {
        return $this->following()->count();
    }

    public function getCanUploadAttribute($value) {
        return (boolean) Role::getValue('artist_allow_upload');
    }

    public function getTrackSkipLimitAttribute($value) {
        return intval(Role::getValue('option_track_skip_limit'));
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function ban() {
        return $this->hasOne(Banned::class);
    }

    public function group()
    {
        return $this->hasOne(RoleUser::class)->with('role');
    }

    public function history(){
        return $this->hasMany(History::class)->with('song');
    }

    public function collection(){
        return Song::leftJoin('collections', (new Song())->getTable() . '.id', '=', 'collections.collectionable_id')
            ->select((new Song())->getTable().'.*', 'collections.user_id as host_id')
            ->where('collections.user_id', $this->id)
            ->where('collections.collectionable_type', 'App\Models\Song');
    }

    public function loved(){
        return Song::leftJoin('love', (new Song())->getTable() . '.id', '=', 'love.loveable_id')
            ->select((new Song())->getTable().'.*', 'love.user_id as host_id')
            ->where('love.user_id', $this->id)
            ->where('love.loveable_type', 'App\Models\Song');
    }

    public function favoriteArtists(){
        return $this->hasMany(Love::class)
            ->where('loveable_type', 'App\Models\Artist')
            ->leftJoin((new Artist())->getTable(), (new Love())->getTable() . '.loveable_id', '=', (new Artist())->getTable() . '.id');
    }

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class)->where('activities.action', '!=', 'addEvent');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function followers()
    {
        return $this->belongsToMany(self::class, 'love', 'loveable_id', 'user_id')->where('loveable_type', 'App\Models\User');
    }

    public function following()
    {
        return $this->belongsToMany(self::class, 'love', 'user_id', 'loveable_id')->where('loveable_type', 'App\Models\User');
    }

    public function artist(){
        return $this->hasOne(Artist::class, 'id', 'artist_id');
    }

    public function feed(){
        return Activity::leftJoin('love', 'activities.user_id', '=', 'love.loveable_id')
            ->select('activities.*', 'love.user_id as host_id')
            ->where('love.loveable_type', 'App\Models\User')
            ->where('love.user_id', $this->id);
    }

    public function recent(){
        return Song::leftJoin('histories', (new Song())->getTable() . '.id', '=', 'histories.historyable_id')
            ->select((new Song())->getTable().'.*', 'histories.user_id as host_id')
            ->where('histories.user_id', $this->id)
            ->where('histories.historyable_type', 'App\Models\Song');
    }

    public function communitySongs(){
        return Song::leftJoin('histories', (new Song())->getTable() . '.id', '=', 'histories.historyable_id')
            ->leftJoin('love', 'histories.user_id', '=', 'love.loveable_id')
            ->select((new Song())->getTable().'.*', 'histories.user_id as host_id', 'love.user_id as love_id')
            ->where('histories.historyable_type', 'App\Models\Song')
            ->where('love.user_id', $this->id)
            ->groupBy('histories.historyable_id');
    }

    public function obsessed(){
        return Song::leftJoin('histories', (new Song())->getTable() . '.id', '=', 'histories.historyable_id')
            ->select((new Song())->getTable().'.*', 'histories.user_id as host_id')
            ->where('histories.user_id', $this->id)
            ->where('histories.historyable_type', 'App\Models\Song')
            ->where('histories.interaction_count', '>', 3)
            ->groupBy('histories.historyable_id')
            ->orderBy('histories.interaction_count', 'desc');
    }

    public function getNotificationCountAttribute(){
        $count = 0;
        $count = $count + Activity::leftJoin('love', 'activities.activityable_id', '=', 'love.loveable_id')
                ->select('activities.*', 'love.user_id as host_id')
                ->where('love.loveable_type', 'App\Models\Playlist')
                ->where('activities.action', '!=', 'followPlaylist')
                ->where('activities.action', '!=', 'followUser')
                ->where('activities.action', '!=', 'playSong')
                ->where('activities.action', '!=', 'postFeed')
                ->where('love.user_id', $this->id)
                ->where('activities.created_at', '>', $this->last_seen_notif)
                ->count();

        $count = $count + Activity::leftJoin('love', 'activities.activityable_id', '=', 'love.loveable_id')
                ->select('activities.*', 'love.user_id as host_id')
                ->where('activities.action', '!=', 'followArtist')
                ->where('activities.action', '!=', 'postFeed')
                ->where('love.loveable_type', 'App\Models\Artist')
                ->where('love.user_id', $this->id)
                ->where('activities.created_at', '>', $this->last_seen_notif)
                ->where(function ($query) {
                    $query->where('action', 'addSong')
                        ->orWhere('action', 'addEvent');
                })
                ->count();
        $count = $count +  $this->hasMany(Notification::class)->where('created_at', '>', $this->last_seen_notif)->count();

        return $count;
    }

    public function notifications(){
        $notifications = new Collection();
        $notifications = $notifications->merge(Activity::leftJoin('love', 'activities.activityable_id', '=', 'love.loveable_id')
            ->select('activities.*', 'love.user_id as host_id')
            ->where('love.loveable_type', 'App\Models\Playlist')
            ->where('activities.action', '!=', 'followPlaylist')
            ->where('activities.action', '!=', 'followUser')
            ->where('activities.action', '!=', 'playSong')
            ->where('activities.action', '!=', 'postFeed')
            ->where('love.user_id', $this->id)
            ->latest()
            ->paginate(10));
        $notifications = $notifications->merge(Activity::leftJoin('love', 'activities.activityable_id', '=', 'love.loveable_id')
            ->select('activities.*', 'love.user_id as host_id')
            ->where('activities.action', '!=', 'followArtist')
            ->where('activities.action', '!=', 'postFeed')
            ->where('love.loveable_type', 'App\Models\Artist')
            ->where('love.user_id', $this->id)
            ->where(function ($query) {
                $query->where('action', 'addSong')
                    ->orWhere('action', 'addEvent');
            })
            ->latest()
            ->paginate(10));

        $notifications = $notifications->merge($this->hasMany(Notification::class)->paginate(10));

        return $notifications->sortByDesc('created_at');
    }


    public function collaborations(){
        return Playlist::leftJoin('collaborators', (new Playlist())->getTable() . '.id', '=', 'collaborators.playlist_id')
            ->select((new Playlist())->getTable().'.*', 'collaborators.user_id as host_id')
            ->where('collaborators.friend_id', $this->id)
            ->where('collaborators.approved', 1);
    }

    public function subscribed(){
        return Playlist::leftJoin('love', (new Playlist())->getTable() . '.id', '=', 'love.loveable_id')
            ->select((new Playlist())->getTable().'.*', 'love.user_id as host_id')
            ->where('love.user_id', $this->id)
            ->where('love.loveable_type', 'App\Models\Playlist');
    }

    public function subscription(){
        return $this->hasOne(Subscription::class);
    }

    public function connect(){
        return $this->hasMany(Connect::class);
    }

    public function getPostCountAttribute($value)
    {
        return Post::where('user_id', $this->id)->count();
    }

    public function getSongCountAttribute($value)
    {
        return Song::where('user_id', $this->id)->count();
    }

    public function getAlbumCountAttribute($value)
    {
        return Album::where('user_id', $this->id)->count();
    }

    public function getArtistCountAttribute($value)
    {
        return Artist::where('user_id', $this->id)->count();
    }

    public function delete()
    {
        Comment::where('commentable_type', $this->getMorphClass())->where('commentable_id', $this->id)->delete();
        Love::where('loveable_type', $this->getMorphClass())->where('loveable_id', $this->id)->delete();
        Notification::where('notificationable_type', $this->getMorphClass())->where('notificationable_id', $this->id)->delete();
        Notification::where('user_id', $this->id)->delete();
        Activity::where('activityable_type', $this->getMorphClass())->where('activityable_id', $this->id)->delete();
        Activity::where('user_id', $this->id)->delete();
        RoleUser::where('user_id', $this->id)->delete();
        Banned::where('user_id', $this->id)->delete();
        Playlist::where('user_id', $this->id)->delete();
        Connect::where('user_id', $this->id)->delete();
        Slide::where('user_id', $this->id)->delete();
        Channel::where('user_id', $this->id)->delete();
        ArtistRequest::where('user_id', $this->id)->delete();
        Report::where('user_id', $this->id)->delete();

        return parent::delete();
    }
}
