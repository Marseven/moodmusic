<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 08:47
 */

namespace App\Models;
;
use Illuminate\Database\Eloquent\Model;

class Popular extends Model
{
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'song_id', 'podcast_id', 'station_id', 'album_id', 'episode_id', 'created_at', 'artist_id', 'genre', 'plays'
    ];

    protected $table = 'popular';
}
