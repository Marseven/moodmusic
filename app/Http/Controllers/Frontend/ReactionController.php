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
use App\Models\Reaction;

class ReactionController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function react() {
        $this->request->validate([
            'reaction_able_id' => 'required|int',
            'reaction_able_type' => 'required|in:App\Models\Activity,App\Models\Comment',
            'reaction_type' => 'required|string|in:' . config('settings.reactions', 'like,love,haha,vow,sad,angry'),
        ]);

        $reaction = Reaction::updateOrCreate(
            [
                'user_id' => auth()->user()->id,
                'reactionable_id' => $this->request->input('reaction_able_id'),
                'reactionable_type' => $this->request->input('reaction_able_type'),
            ],
            [
                'type' => $this->request->input('reaction_type')
            ]
        );

        $reactionAble = $this->request->input('reaction_able_type');
        $reactionAble = (new $reactionAble)->find($this->request->input('reaction_able_id'));
        $reactionAble->increment('reaction_count');
        if($reactionAble->user_id != auth()->user()->id) {
            $notification = Notification::where('object_id', $this->request->input('reaction_able_id'))
                ->where('user_id', $reactionAble->user_id)
                ->where('notificationable_id', $reaction->id)
                ->where('notificationable_type', $reaction->getMorphClass())->first();
            if(! isset($notification->id)) {
                pushNotification(
                    $reactionAble->user_id,
                    $reaction->id,
                    $reaction->getMorphClass(),
                    'reactComment',
                    $this->request->input('reaction_able_id')
                );

            }
        }

        return response()->json(['success' => true]);
    }

    public function revoke() {
        $this->request->validate([
            'reaction_able_id' => 'required|int',
            'reaction_able_type' => 'required|in:App\Models\Activity,App\Models\Comment'
        ]);

        $reaction = Reaction::where('user_id', auth()->user()->id)
            ->where('reactionable_id', $this->request->input('reaction_able_id'))
            ->where('reactionable_type', $this->request->input('reaction_able_type'))
            ->firstOrFail();

        $class = $this->request->input('reaction_able_type');
        (new $class)::where('id', $this->request->input('reaction_able_id'))->decrement('reaction_count');

        $reaction->delete();

        return response()->json(['success' => true]);
    }
}