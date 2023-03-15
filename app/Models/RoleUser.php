<?php
/**
 * Created by PhpStorm.
 * User: lechchut
 * Date: 7/29/19
 * Time: 1:18 PM
 */

namespace App\Models;

use App\Traits\SanitizedRequest;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    use SanitizedRequest;

    protected $table = 'role_users';

    protected $fillable = ['user_id', 'role_id', 'end_at'];

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
}