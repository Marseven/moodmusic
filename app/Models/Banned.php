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

class Banned extends Model
{
    use SanitizedRequest;

    protected $table = 'banned';

    protected $fillable = ['user_id', 'reason', 'end_at', 'ip'];

}