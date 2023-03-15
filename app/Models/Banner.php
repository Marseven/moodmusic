<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 08:47
 */

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class Banner extends Model implements HasMedia
{
    protected $hidden = ['media'];

    use InteractsWithMedia;
}