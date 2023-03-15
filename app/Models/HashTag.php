<?php

namespace App\Models;

use App\Traits\SanitizedRequest;
use Illuminate\Database\Eloquent\Model;

class HashTag extends Model
{
    protected $table = 'hash_tags';
}