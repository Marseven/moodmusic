<?php
/**
 * Created by NiNaCoder.
 * Date: 2020-05-26
 * Time: 11:12
 */

namespace App\Models;

use App\Traits\SanitizedRequest;
use Illuminate\Database\Eloquent\Model;
use DB;


class Connect extends Model
{
    use SanitizedRequest;

    protected $table = 'oauth_socialite';

    protected $fillable = ['user_id', 'provider_id', 'provider_name', 'provider_email', 'provider_artwork', 'service'];

}