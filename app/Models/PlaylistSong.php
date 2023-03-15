<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaylistSong extends Model
{
    protected $table = 'playlist_songs';

    protected $fillable = [
        'song_id', 'playlist_id'
    ];
}