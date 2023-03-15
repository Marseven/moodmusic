<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class PlaylistLog extends Model {

    protected $table = 'playlist_spotify_logs';

    protected static function boot()
    {
        parent::boot();
    }

}
