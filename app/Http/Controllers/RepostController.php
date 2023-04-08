<?php

namespace App\Http\Controllers;

use App\Album;
use App\Repost;
use App\User;
use Auth;
use Common\Core\BaseController;
use Illuminate\Http\Request;

class RepostController extends BaseController
{
    public function __construct(protected Repost $repost, protected Request $request)
    {
    }

    public function index(User $user)
    {
        $this->authorize('show', $user);

        $pagination = $user
            ->reposts()
            ->with('repostable.artists')
            ->simplePaginate(20);

        [$albums, $tracks] = $pagination
            ->filter(function (Repost $repost) {
                return !is_null($repost->repostable);
            })
            ->partition(function (Repost $repost) {
                return $repost->repostable->model_type === Album::MODEL_TYPE;
            });

        $albums->load('repostable.tracks');

        $pagination->setCollection($tracks->concat($albums)->values());

        return $this->success(['pagination' => $pagination]);
    }

    public function toggle()
    {
        $this->middleware('auth');

        $userId = Auth::id();
        $repostableType = modelTypeToNamespace(
            $this->request->get('repostable_type'),
        );

        $table = $repostableType === Album::class ? 'albums' : 'tracks';
        $this->validate($this->request, [
            'repostable_type' => 'required',
            'repostable_id' => "required|exists:$table,id",
        ]);

        $existingRepost = $this->repost
            ->where('user_id', $userId)
            ->where('repostable_id', $this->request->get('repostable_id'))
            ->where('repostable_type', $repostableType)
            ->first();

        if ($existingRepost) {
            $existingRepost->delete();
            return $this->success(['action' => 'removed']);
        } else {
            $newRepost = $this->repost->create([
                'user_id' => $userId,
                'repostable_id' => $this->request->get('repostable_id'),
                'repostable_type' => $repostableType,
            ]);
            return $this->success([
                'action' => 'added',
                'repost' => $newRepost,
            ]);
        }
    }
}
