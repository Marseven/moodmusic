<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 21:22
 */

namespace App\Models;

use App\Traits\SanitizedRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;
use Route;

class Channel extends Model
{
    use SanitizedRequest;

    protected $appends = ['objects', 'permalink_url'];

    public function getObjectsAttribute($value)
    {
        switch ($this->attributes['attraction']) {
            case 'topWeek':
                $subTime = Carbon::now()->subWeek();
                break;
            case 'topMonth':
                $subTime = Carbon::now()->subMonth();
                break;
            case 'topYear':
                $subTime = Carbon::now()->subYear();
                break;
            default:
                $subTime = Carbon::now()->subDay();
                break;
        }

        $result = new \stdClass();

        if ($this->attributes['object_type'] == "artist") {
            $result = new Artist();
        } elseif ($this->attributes['object_type'] == "song") {
            $result = new Song();
        } elseif ($this->attributes['object_type'] == "playlist") {
            $result = new Playlist();
        } elseif ($this->attributes['object_type'] == "album") {
            $result = new Album();
        } elseif ($this->attributes['object_type'] == "station") {
            $result = new Station();
        } elseif ($this->attributes['object_type'] == "user") {
            return User::latest()->paginate(20);
        } elseif ($this->attributes['object_type'] == "podcast") {
            $result = Podcast::with('artist');
        }  elseif ($this->attributes['object_type'] == "video") {
            $result = \App\Modules\Video\Video::with('user');
        }

        if($this->attributes['attraction'] != 'latest' && ! $this->attributes['object_ids']) {
            $result = $result->leftJoin('popular', 'popular.' . $this->attributes['object_type'] . '_id', '=', $this->attributes['object_type'] . 's.id')
                ->select($this->attributes['object_type'] . 's.*', DB::raw('sum(popular.plays) AS total_plays'));
        }

        if(Route::currentRouteName() == 'frontend.genre') {
            $genre = Genre::where('alt_name', request()->route()->parameter('slug'))->first();
            if(! isset($genre->id)) {
                return null;
            }

            if($this->attributes['attraction'] == 'latest') {
                $result = $result->where('genre', 'REGEXP', '(^|,)(' . $genre->id . ')(,|$)');
            } else {
                $result = $result->where($this->attributes['object_type'] . 's.genre', 'REGEXP', '(^|,)(' . $genre->id . ')(,|$)');
            }
        } elseif(Route::currentRouteName() == 'frontend.channel.genre') {
            $genre = Genre::where('alt_name', request()->route()->parameter('alt_name'))->first();
            if(! isset($genre->id)) {
                return null;
            }

            if($this->attributes['attraction'] == 'latest') {
                $result = $result->where('genre', 'REGEXP', '(^|,)(' . $genre->id . ')(,|$)');
            } else {
                $result = $result->where($this->attributes['object_type'] . 's.genre', 'REGEXP', '(^|,)(' . $genre->id . ')(,|$)');
            }
        } elseif(Route::currentRouteName() == 'frontend.mood') {
            $mood = Genre::where('alt_name',  request()->route()->parameter('slug'))->first();
            if(! isset($mood->id)) {
                return null;
            }
            if($this->attributes['attraction'] == 'latest') {
                $result = $result->where('mood', 'REGEXP', '(^|,)(' . $mood->id . ')(,|$)');
            } else {
                $result = $result->where($this->attributes['object_type'] . 's.mood', 'REGEXP', '(^|,)(' . $mood->id . ')(,|$)');
            }
        } elseif(Route::currentRouteName() == 'frontend.channel.mood') {
            $mood = Genre::where('alt_name',  request()->route()->parameter('alt_name'))->first();
            if(! isset($mood->id)) {
                return null;
            }
            if($this->attributes['attraction'] == 'latest') {
                $result = $result->where('mood', 'REGEXP', '(^|,)(' . $mood->id . ')(,|$)');
            } else {
                $result = $result->where($this->attributes['object_type'] . 's.mood', 'REGEXP', '(^|,)(' . $mood->id . ')(,|$)');
            }
        }

        if($this->attributes['object_ids'] !=  null) {
            $result = $result->whereIn($this->attributes['object_type'] . 's.id', explode(',', $this->attributes['object_ids']));
            $result = $result->orderBy(DB::raw('FIELD(id, ' .  removeGlitchFromArrayList($this->attributes['object_ids']) . ')', 'FIELD'));
        } else {
            if($this->attributes['attraction'] == 'latest') {
                if ($this->attributes['object_type'] == "album") {
                    $result = $result->orderBy('albums.released_at', 'desc');
                } else {
                    $result = $result->latest();
                }
            } else {
                $result = $result->where('popular.created_at', '>=',  $subTime)
                    ->groupBy('popular.' . $this->attributes['object_type'] . '_id')
                    ->orderBy('total_plays', 'desc');
            }
        }

        return $result->paginate(20);
    }

    public function getPermalinkUrlAttribute()
    {
        if(Route::currentRouteName() == 'frontend.genre') {
            return route('frontend.channel.genre', ['alt_name' => request()->route()->parameter('slug'), 'slug' => $this->attributes['alt_name']]);
        } elseif(Route::currentRouteName() == 'frontend.mood') {
            return route('frontend.channel.mood', ['alt_name' => request()->route()->parameter('slug'), 'slug' => $this->attributes['alt_name']]);
        } else {
            return route('frontend.channel', ['slug' => $this->attributes['alt_name']]);
        }
    }
}