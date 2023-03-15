<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-26
 * Time: 15:51
 */

namespace App\Models;

use App\Scopes\PublishedScope;
use Illuminate\Database\Eloquent\Model;
use DB;

class Activity extends Model
{
    protected $appends = ['details'];

    protected $hidden = ['events'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new PublishedScope);
    }

    protected static function booted()
    {
        static::retrieved(function ($activity) {
            $activity->load('user');
        });
    }

    public function activityable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPermalinkUrlAttribute($value)
    {
        return route('frontend.user.posts', ['id' => $this->attributes['id'], 'username' => $this->user->username]);
    }

    public function getDetailsAttribute($value)
    {
        $buffer = new \stdClass();

        if($this->attributes['action'] == "addToPlaylist") {
            $buffer->model = Playlist::withoutGlobalScopes()->find($this->attributes['activityable_id']);
            $buffer->objects = Song::whereIn('id', explode(',', $this->attributes['events']))->get();
        } elseif($this->attributes['action'] == "inviteCollaborate") {
            $buffer->model = Playlist::withoutGlobalScopes()->find($this->attributes['activityable_id']);
        } elseif($this->attributes['action'] == "favoriteSong" || $this->attributes['action'] == "collectSong" || $this->attributes['action'] == "playSong") {
            $buffer->objects = Song::whereIn('id', explode(',', $this->attributes['events']))->get();
        } elseif($this->attributes['action'] == "followUser"){
            $buffer->objects = User::whereIn('id', explode(',', $this->attributes['events']))->get();
        } elseif($this->attributes['action'] == "followPlaylist") {
            $buffer->objects = Playlist::with('user')->whereIn('id', explode(',', $this->attributes['events']))->get();
        } elseif($this->attributes['action'] == "followArtist") {
            $buffer->objects = Artist::whereIn('id', explode(',', $this->attributes['events']))->get();
        } elseif($this->attributes['action'] == "addSong") {
            $buffer->model = Artist::find($this->attributes['activityable_id']);
            $buffer->objects = Song::whereIn('id', explode(',', $this->attributes['events']))->get();
        } elseif($this->attributes['action'] == "addEvent") {
            $buffer->model = Artist::find($this->attributes['activityable_id']);
            $buffer->objects = Event::whereIn('id', explode(',', $this->attributes['events']))->get();
        } elseif($this->attributes['action'] == "postFeed") {
            switch ($this->attributes['activityable_type']) {
                case (new Song)->getMorphClass():
                    $buffer->objects = Song::where('id', $this->attributes['activityable_id'])->get();
                    break;
                case (new Album)->getMorphClass():
                    $buffer->objects = Album::where('id', $this->attributes['activityable_id'])->get();
                    break;
                case (new Artist)->getMorphClass():
                    $buffer->objects = Artist::where('id', $this->attributes['activityable_id'])->get();
                    break;
                case (new Playlist)->getMorphClass():
                    $buffer->objects = Playlist::withoutGlobalScopes()->where('id', $this->attributes['activityable_id'])->get();
                    break;
            }
        }

        return $buffer;
    }

    public function delete()
    {
        Comment::where('commentable_type', $this->getMorphClass())->where('commentable_id', $this->id)->delete();
        Notification::where('notificationable_type', $this->getMorphClass())->where('notificationable_id', $this->id)->delete();

        return parent::delete();
    }
}