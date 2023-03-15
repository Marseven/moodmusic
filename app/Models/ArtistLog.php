<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class ArtistLog extends Model {

    protected $table = 'artist_spotify_logs';

    protected static function boot()
    {
        parent::boot();
    }

}
