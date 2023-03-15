<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class SongTag extends Model {

    protected $table = 'song_tags';

    public function songs() {
        return Song::leftJoin('song_tags', 'song_tags.song_id', '=', (new Song)->getTable() . '.id')
            ->select('songs.*', 'song_tags.id as host_id')
            ->where('song_tags.tag', $this->tag);
    }

    public function getPermalinkUrlAttribute($value)
    {
        return route('frontend.tag', ['tag' => str_slug($this->attributes['tag'])]);
    }
}
