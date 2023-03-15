<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-24
 * Time: 20:12
 */

namespace App\Http\Controllers\Backend;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use View;
use App\Models\Comment;
use Storage;
use App\Models\Artist;
use App\Models\Playlist;
use App\Models\Song;
use App\Models\Album;
use App\Models\Station;
use Carbon\Carbon;
use Route;

class CommentsController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        if(Route::currentRouteName() == 'backend.comments.approved') {
            $comments = Comment::withoutGlobalScopes()->where('approved', 1)->latest()->paginate(20);
        } else {
            $comments = Comment::withoutGlobalScopes()->where('approved', 0)->latest()->paginate(20);
        }

        return view('backend.comments.index')
            ->with('comments', $comments);
    }

    public function delete()
    {
        $comment = Comment::withoutGlobalScopes()->where('id', $this->request->route('id'))->firstOrFail();

        if($comment->parent_id) {
            Comment::withoutGlobalScopes()->where('id', $comment->parent_id)->decrement('reply_count');
        } else {
            switch ($comment->commentable_type) {
                case 'App\Models\Activity':
                    Activity::where('id', $comment->commentable_id)->decrement('comment_count');
                    break;
                case 'App\Models\Album':
                    Album::where('id', $comment->commentable_id)->decrement('comment_count');
                    break;
                case 'App\Models\Artist':
                    Artist::where('id', $comment->commentable_id)->decrement('comment_count');
                    break;
                case 'App\Models\Playlist':
                    Playlist::where('id', $comment->commentable_id)->decrement('comment_count');
                    break;
                case 'App\Models\User':
                    User::where('id', $comment->commentable_id)->decrement('comment_count');
                    break;
                case 'App\Models\Song':
                    Song::where('id', $comment->commentable_id)->decrement('comment_count');
                    break;
            }
        }

        $comment->delete();

        return redirect()->route('backend.comments')->with('status', 'success')->with('message', 'Comment successfully deleted!');
    }

    public function edit()
    {
        $comment = Comment::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        return view('backend.comments.edit')
            ->with('comment', $comment);
    }

    public function editPost()
    {
        $this->request->validate([
            'content' => 'required|string',
            'created_at' => 'date_format:Y/m/d H:i',
            'approved' => 'required|boolean',
        ]);

        $comment = Comment::withoutGlobalScopes()->findOrFail($this->request->route('id'));
        $comment->content = $this->request->input('content');
        $comment->created_at = Carbon::parse($this->request->input('created_at'))->format('Y/m/d H:i');
        $comment->approved = $this->request->input('approved');
        $comment->save();

        return redirect()->route('backend.comments')->with('status', 'success')->with('message', 'Comment successfully updated!');
    }

    public function approve()
    {
        $this->request->validate([
            'id' => 'required|integer',
        ]);

        $comment = Comment::withoutGlobalScopes()->findOrFail($this->request->input('id'));
        $comment->approved = 1;
        $comment->save();

        return response()->json(['success' => true]);
    }
}