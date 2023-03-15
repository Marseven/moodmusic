<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 16:54
 */

namespace App\Models;

use App\Traits\SanitizedRequest;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use SanitizedRequest;

    protected $fillable = ['user_id', 'reactionable_id', 'reactionable_type', 'type'];

    protected $hidden = ['id', 'updated_at', 'reactionable_id', 'reactionable_type'];

    public function reactionable()
    {
        return $this->morphTo();
    }

    public function getObjectAttribute($value)
    {
        return (new $this->attributes['reactionable_type'])::find($this->attributes['reactionable_id']);;
    }
}