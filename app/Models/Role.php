<?php

namespace App\Models;

use App\Traits\SanitizedRequest;
use Illuminate\Database\Eloquent\Model;
use Cache;

class Role extends Model
{
    use SanitizedRequest;

    protected $fillable = [
        'name', 'slug', 'permissions',
    ];
    protected $casts = [
        'permissions' => 'array',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_users');
    }

    public function hasAccess(array $permissions) : bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission))
                return true;
        }
        return false;
    }

    static public function groupId()
    {
        if(! auth()->check()) {
            return 6;
        } else {
            return isset(auth()->user()->group->role_id) ? auth()->user()->group->role_id : 6;
        }
    }

    private function hasPermission(string $permission) : bool
    {
        if(! auth()->check()) {
            $groupNumber = 6;
        } else {
            $groupNumber = isset(auth()->user()->group->role_id) ? auth()->user()->group->role_id : 6;
        }

        if(Cache::has('usergroup')) {
            $roles = Cache::get('usergroup');
        } else {
            $roles = Role::all();
            Cache::forever('usergroup', $roles);
        }

        $role = $roles->firstWhere('id', $groupNumber);

        return $role[$permission] ?? false;
    }

    static public function getValue($permission, $default = null)
    {
        if(! auth()->check()) {
            $groupNumber = 6;
        } else {
            $groupNumber = isset(auth()->user()->group->role_id) ? auth()->user()->group->role_id : 6;
        }

        if(Cache::has('usergroup')) {
            $roles = Cache::get('usergroup');
        } else {
            $roles = Role::all();
            Cache::forever('usergroup', $roles);
        }

        $role = $roles->firstWhere('id', $groupNumber);

        if(isset($role) && isset($role->permissions[$permission])) {
            return $role->permissions[$permission];
        } else {
            return $default;
        }
    }

    static public function getUserValue($permission, $user_id)
    {

        $user = User::find($user_id);

        $groupNumber = isset($user->group->role_id) ? $user->group->role_id : 6;

        if(Cache::has('usergroup')) {
            $roles = Cache::get('usergroup');
        } else {
            $roles = Role::all();
            Cache::forever('usergroup', $roles);
        }

        $role = $roles->firstWhere('id', $groupNumber);

        if(isset($role) && isset($role->permissions[$permission])) {
            return $role->permissions[$permission];
        } else {
            return null;
        }
    }
}