<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-28
 * Time: 13:41
 */

namespace App\Models;

use DB;
use Auth;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $appends = ['details'];

    public function getDetailsAttribute($value)
    {
        $buffer = new \stdClass();
        $buffer->object = (new $this->attributes['notificationable_type'])::find($this->attributes['notificationable_id']);
        $buffer->host = User::find($this->attributes['hostable_id']);
        return $buffer;
    }
}