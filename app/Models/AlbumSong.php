<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlbumSong extends Model
{
    protected $table = 'album_songs';

    protected $fillable = [
        'song_id', 'album_id'
    ];
}