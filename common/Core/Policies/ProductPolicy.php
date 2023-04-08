<?php

namespace Common\Core\Policies;

use App\User;
use Common\Settings\Settings;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    use HandlesAuthorization;

    public function __construct(protected Settings $settings)
    {
    }

    public function index(User $user): bool|Response
    {
        return $this->settings->get('billing.enable') ||
            $user->hasPermission('plans.view');
    }

    public function show(User $user): bool|Response
    {
        return $this->settings->get('billing.enable') ||
            $user->hasPermission('plans.view');
    }

    public function store(User $user): bool|Response
    {
        return $user->hasPermission('plans.create');
    }

    public function update(User $user): bool|Response
    {
        return $user->hasPermission('plans.update');
    }

    public function destroy(User $user): bool|Response
    {
        return $user->hasPermission('plans.delete');
    }
}
