<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-24
 * Time: 13:24
 */

namespace App\Models;

use App\Scopes\ApprovedScope;
use App\Scopes\PublishedScope;
use App\Scopes\VisibilityScope;
use App\Traits\SanitizedRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Facades\URL;
use DB;
use Auth;
use Module;

class Lyric extends Model
{
    use SanitizedRequest;

    protected $table = 'lyrics';
}

