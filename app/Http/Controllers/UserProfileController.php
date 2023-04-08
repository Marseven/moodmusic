<?php

namespace App\Http\Controllers;

use App\User;
use App\UserProfile;
use Auth;
use Common\Auth\Events\UserAvatarChanged;
use Common\Core\BaseController;
use Illuminate\Http\Request;

class UserProfileController extends BaseController
{
    public function __construct(protected Request $request)
    {
    }

    public function show(User $user)
    {
        $relations = array_merge(
            array_filter(explode(',', $this->request->get('with', ''))),
            ['profile', 'links'],
        );
        $loadCount = array_merge(
            array_filter(explode(',', $this->request->get('withCount', ''))),
            ['followers', 'followedUsers'],
        );

        $user
            ->load($relations)
            ->loadCount($loadCount)
            ->setGravatarSize(220);

        if ($user->id === Auth::id()) {
            $user->load(['tokens']);
        }

        if (!$user->getRelation('profile')) {
            $user->setRelation('profile', new UserProfile());
        }

        $this->authorize('show', $user);

        $options = [
            'prerender' => [
                'view' => 'user.show',
                'config' => 'user.show',
            ],
        ];

        return $this->success(
            [
                'user' => $user,
            ],
            200,
            $options,
        );
    }

    public function update()
    {
        $user = Auth::user();
        $this->authorize('update', $user);

        $userData = $this->request->get('user');

        $profileData = $this->request->get('profile');

        User::unguard(true);
        $oldAvatar = $user->avatar;
        $user->fill($userData)->save();

        if (isset($userData['avatar']) && $oldAvatar !== $userData['avatar']) {
            event(new UserAvatarChanged($user));
        }

        $profile = $user
            ->profile()
            ->updateOrCreate(['user_id' => $user->id], $profileData);

        $user->links()->delete();
        $links = $user->links()->createMany($this->request->get('links'));

        $user->setRelation('profile', $profile);
        $user->setRelation('links', $links);

        return $this->success(['user' => $user]);
    }
}
