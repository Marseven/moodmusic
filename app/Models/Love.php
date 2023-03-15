<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 17:04
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Love extends Model
{
    protected $fillable = ['user_id' ,'loveable_id' ,'loveable_type'];

    public function loveable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function artist()
    {
        return $this->belongsToMor(Artist::class, 'loveable');
    }

    public function getMorphObjectAttribute()
    {
        return (new \App\Artist($this));
    }

}