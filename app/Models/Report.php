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

class Report extends Model
{
    use SanitizedRequest;

    protected $table = 'reports';

    protected $fillable = [
        'user_id', 'reportable_id', 'reportable_type', 'message'
    ];

    public function getObjectAttribute()
    {
        if($this->reportable_type == 'App\\Models\\Song' || $this->reportable_type == 'App\\Models\\Podcast' || $this->reportable_type == 'App\\Models\\Episode')
            return (new $this->reportable_type)::find($this->reportable_id);
        else return [];
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}