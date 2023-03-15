<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-24
 * Time: 13:24
 */

namespace App\Models;

use App\Scopes\VisibilityScope;
use App\Traits\SanitizedRequest;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use SanitizedRequest;

    protected $table = 'regions';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new VisibilityScope());
    }
}
