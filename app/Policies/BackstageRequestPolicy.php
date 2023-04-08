<?php

namespace App\Policies;

use Common\Auth\BaseUser;
use App\BackstageRequest;
use Common\Core\Policies\BasePolicy;

class BackstageRequestPolicy extends BasePolicy
{
    public function index(BaseUser $user, $userId = null)
    {
        return $user->hasPermission('backstageRequests.view') || $user->id === (int) $userId;
    }

    public function show(BaseUser $user, BackstageRequest $backstageRequest)
    {
        return $user->hasPermission('backstageRequests.view') || $backstageRequest->user_id === $user->id;
    }

    public function store(BaseUser $user)
    {
        return !!$user->id;
    }

    public function update(BaseUser $user, BackstageRequest $backstageRequest)
    {
        return $user->hasPermission('backstageRequests.update') || $backstageRequest->user_id === $user->id;
    }

    public function destroy(BaseUser $user, $backstageRequestIds)
    {
        if ($user->hasPermission('backstageRequests.delete')) {
            return true;
        } else {
            $dbCount = app(BackstageRequest::class)
                ->whereIn('id', $backstageRequestIds)
                ->where('user_id', $user->id)
                ->count();
            return $dbCount === count($backstageRequestIds);
        }
    }

    public function handle(BaseUser $user)
    {
        return $user->hasPermission('backstageRequests.handle');
    }
}
