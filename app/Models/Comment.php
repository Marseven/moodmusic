<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 16:54
 */

namespace App\Models;

use App\Scopes\ApprovedScope;
use Illuminate\Database\Eloquent\Model;
use DB;

class Comment extends Model
{
    protected $appends = ['time_elapsed', 'replies', 'reactions', 'reacted'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ApprovedScope);
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function getContentAttribute()
    {
        return nl2br(mentionToLink($this->attributes['content'], false));
    }

    public function getTimeElapsedAttribute()
    {
        return timeElapsedShortString($this->attributes['created_at']);
    }

    public function getRepliesAttribute()
    {
        return $this->getReplies($this->id, 2);
    }

    public function getReactionsAttribute()
    {
        return Reaction::where('reactionable_id', $this->id)
            ->select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->where('reactionable_type', 'App\Models\Comment')
            ->get();
    }

    public function getReactedAttribute()
    {
        if(auth()->check()) {
            return Reaction::where('reactionable_id', $this->id)
                ->where('reactionable_type', 'App\Models\Comment')
                ->where('user_id', auth()->user()->id)
                ->first();
        } else {
            return null;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getReplies($parrentId, $limit = 10){
        $replies = Comment::with('user')
            ->where('parent_id', $parrentId)
            ->paginate($limit, ['*'], 'page', 1);
        $replies->setPath(route('frontend.comments.get.replies'));
        return $replies;
    }

    public function getObjectAttribute()
    {
        return (new $this->commentable_type)::find($this->commentable_id);
    }

    public function delete()
    {
        Reaction::where('reactionable_type', $this->getMorphClass())->where('reactionable_id', $this->id)->delete();
        Notification::where('notificationable_type', $this->getMorphClass())->where('notificationable_id', $this->id)->delete();

        return parent::delete();
    }
}