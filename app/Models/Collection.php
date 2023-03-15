<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 17:04
 */

namespace App\Models;

use App\Traits\SanitizedRequest;
use Illuminate\Database\Eloquent\Model;


class Collection extends Model
{
    use SanitizedRequest;

    protected $fillable = ['user_id' ,'collectionble_id' ,'collectionable_type'];

    public function loveable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}