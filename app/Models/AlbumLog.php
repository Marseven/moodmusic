<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class AlbumLog extends Model {

    protected $table = 'album_spotify_logs';

    protected static function boot()
    {
        parent::boot();
    }

}
