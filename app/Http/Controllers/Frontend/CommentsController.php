<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 18:07
 */

namespace App\Http\Controllers\Frontend;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\Comment;
use App\Models\Email;
use View;
use App\Models\Role;
use \Illuminate\Pagination\LengthAwarePaginator;

class CommentsController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function add() {
        $this->request->merge(array('comment' => trim(strip_tags( preg_replace("/\s|&nbsp;/",' ', $this->request->input('content'))))));

        $this->request->validate([
            'commentable_id' => 'required|int',
            'commentable_type' => 'required|in:App\Models\Song,App\Models\Artist,App\Models\Album,App\Models\Station,App\Models\Playlist,App\Models\Post,App\Models\User,App\Models\Activity,App\Models\Podcast,App\Models\Episode,App\Modules\Video\Video',
            'comment' => 'required|string|min:' . config('settings.comment_min_chars', 1) . '|max:' . config('settings.comment_max_chars', 180),
        ]);

        $commentContent = trim(strip_tags(br2nl(preg_replace("/\s|&nbsp;/",' ', $this->request->input('content'))), '<tag>'));

        $comment = new Comment();
        $comment->commentable_id = $this->request->input('commentable_id');
        $comment->commentable_type = $this->request->input('commentable_type');
        $comment->content = $commentContent;
        $comment->user_id =  auth()->user()->id;
        $comment->ip = request()->ip();
        $comment->approved = Role::getValue('comment_modc') ? 0 : 1;
        $comment->save();

        //Send stats
        (new $comment->commentable_type)::where('id', $comment->commentable_id)->increment('comment_count');

        $comment->load('user');

        if(config('settings.comment_notif_admin')) {
            (new Email)->newComment($comment);
        }

        if(Role::getValue('comment_modc')) {
            return response()->json([
                'success' => true,
                'moderation' => true,
                'message' => trans('web.POPUP_COMMENT_MODERATION')
            ], 200);
        } else {

            //Notify to commentable author
            $commentable = (new $comment->commentable_type)::find($comment->commentable_id);

            pushNotification(
                $commentable->getMorphClass() == 'App\Models\User' ? $comment->commentable_id : $commentable->user_id,
                $commentable->id,
                (new $comment->commentable_type)->getMorphClass(),
                'commentMusic',
                $comment->id
            );
            pushNotificationMentioned(
                $commentContent,
                $commentable->id,
                (new $comment->commentable_type)->getMorphClass(),
                'commentMentioned',
                $comment->id
            );

            $comment->moderation = false;
            return response()->json($comment);
        }
    }

    public function reply(){
        $this->request->merge(array('comment' => trim(strip_tags( preg_replace("/\s|&nbsp;/",' ',$this->request->input('content'))))));

        $this->request->validate([
            'parent_id' => 'required|int',
            'comment' => 'required|string|min:' . config('settings.comment_min_chars', 1) . '|max:' . config('settings.comment_max_chars', 180),
        ]);

        $comment = Comment::where('id', $this->request->input('parent_id'))->firstOrFail();
        $comment->increment('reply_count');

        $commentContent = trim(strip_tags(br2nl(preg_replace("/\s|&nbsp;/",' ',$this->request->input('content'))), '<tag>'));

        $reply = new Comment();
        $reply->parent_id =  $this->request->input('parent_id');
        $reply->commentable_id = $comment->commentable_id;
        $reply->commentable_type = $comment->commentable_type;
        $reply->content = $commentContent;
        $reply->ip = request()->ip();
        $reply->approved = Role::getValue('comment_modc') ? 0 : 1;
        $reply->user_id = auth()->user()->id;
        $reply->save();
        $reply->load('user');

        pushNotification(
            $comment->user_id,
            $comment->commentable_id,
            $comment->commentable_type,
            'replyComment',
            $reply->id
        );

        pushNotificationMentioned(
            $commentContent,
            $comment->commentable_id,
            $comment->commentable_type,
            'commentMentioned',
            $reply->id
        );

        return response()->json($reply);
    }

    public function getReplies(){
        $this->request->validate([
            'parent_id' => 'required|int',
        ]);

        $per_page = 5;
        $query = Comment::with('user')
            ->where('parent_id', $this->request->input('parent_id'));
        $count = $query->count();
        $page = request()->get('page') ?? 1;
        $first_page = 2;
        $perPage = $page == 1 ? $first_page : $per_page;
        $offset = ($page - 2) * $perPage + $first_page;
        $reviews = $query->skip($offset)->take($perPage)->get();

        return new LengthAwarePaginator(
            $reviews, $count, $per_page, $page, ['path'  => request()->url(), 'query' => request()->query()]
        );
    }

    public function getComments(){
        $this->request->validate([
            'commentable_id' => 'required|int',
            'commentable_type' => 'required|in:App\Models\Song,App\Models\Artist,App\Models\Album,App\Models\Station,App\Models\Playlist,App\Models\Post,App\Models\User,App\Models\Activity,App\Models\Podcast,App\Models\Episode,App\Modules\Video\Video',
        ]);

        $comments =  Comment::with('user')
            ->where('commentable_type', $this->request->input('commentable_type'))
            ->where('commentable_id', $this->request->input('commentable_id'))
            ->whereNull('parent_id')
            ->latest()
            ->paginate(5);

        return response()->json($comments);
    }

    public function show(){
        $this->request->validate([
            'commentable_id' => 'required|int',
            'commentable_type' => 'required|in:App\Models\Song,App\Models\Artist,App\Models\Album,App\Models\Station,App\Models\Playlist,App\Models\Post,App\Models\User,App\Models\Activity,App\Models\Podcast,App\Models\Episode,App\Modules\Video\Video',
        ]);

        $object = new \stdClass();
        $object->type = $this->request->input('commentable_type');
        $object->id = $this->request->input('commentable_id');
        $object->title = '';

        $view = View::make('comments.index')
            ->with('object', $object);

        return $view;
    }

    public function getCommentTemplate() {
        return View::make('comments.templates.comment');
    }

    public function getReplyTemplate() {
        return View::make('comments.templates.reply');
    }

    public function getEmojiTemplate() {
        return View::make('comments.templates.emoji');
    }

    public function editComment() {
        $this->request->validate([
            'id' => 'required|int',
        ]);

        $comment = Comment::findOrFail($this->request->input('id'));

        if($comment->user_id != auth()->user()->id) {
            abort(403);
        }

        $comment->content = $this->mentionToRaw(strip_tags($comment->content));
        return View::make('comments.edit')->with('comment', $comment);
    }

    public function saveComment() {
        $this->request->merge(array('comment' => trim(strip_tags( preg_replace("/\s|&nbsp;/",' ',$this->request->input('content'))))));

        $this->request->validate([
            'id' => 'required|int',
            'comment' => 'required|string|min:' . config('settings.comment_min_chars', 1) . '|max:' . config('settings.comment_max_chars', 180),
        ]);

        $commentContent = trim(strip_tags(br2nl(preg_replace("/\s|&nbsp;/",' ',$this->request->input('content'))), '<tag>'));

        $comment = Comment::findOrFail($this->request->input('id'));
        $comment->content = $commentContent;
        $comment->edited = 1;
        $comment->ip = request()->ip();
        $comment->save();

        return response()->json($comment);
    }

    public function deleteComment() {
        $this->request->validate([
            'id' => 'required|int',
        ]);

        $comment = Comment::findOrFail($this->request->input('id'));

        if($comment->user_id != auth()->user()->id) {
            abort(403);
        }

        $comment->delete();

        return response()->json([
            'success' => true
        ]);
    }

    private function mentionToRaw($string)
    {
        return preg_replace_callback("/<tag\sdata-id=\"(.+?)\"\sdata-username=\"(.+?)\">(.+?)<\/tag>/is", function ($matches) {
            return "<span class=\"atwho-inserted\" contenteditable=\"false\"><tag data-id=\"{$matches[1]}\" data-username=\"{$matches[2]}\">{$matches[3]}</tag></span>";
        }, $string);
    }
}