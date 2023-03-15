<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-24
 * Time: 13:24
 */

namespace App\Models;
use App\Traits\SanitizedRequest;
use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class History extends Model
{
    use SanitizedRequest;

    protected $fillable = [
        'user_id', 'historyable_id', 'historyable_type', 'ownerable_type', 'ownerable_id', 'interaction_count'
    ];

    public function song() {
        return $this->belongsTo(Song::class);
    }
}