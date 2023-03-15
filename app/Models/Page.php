<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 17:04
 */

namespace App\Models;

use App\Traits\SanitizedRequest;
use Illuminate\Database\Eloquent\Model;


class Page extends Model
{
    protected $fillable = ['user_id', 'title', 'alt_name', 'content', 'meta_title', 'meta_description', 'meta_keywords'];
}