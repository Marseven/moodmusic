<?php

namespace App\Models;

use App\Traits\SanitizedRequest;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use SanitizedRequest;

    protected $table = 'role_users';

    protected $fillable = [
        'name', 'slug', 'permissions',
    ];
    protected $casts = [
        'permissions' => 'array',
    ];

    public function permissions(){
        return $this->hasOne(Role::class);
    }
}