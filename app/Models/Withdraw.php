<?php

namespace App\Models;

use App\Traits\SanitizedRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    use SanitizedRequest;

    protected static function booted()
    {
        static::created(function ($model) {
            auth()->user()->decrement('balance', $model->amount);
        });

        static::deleting(function ($model) {
            auth()->user()->increment('balance', $model->amount);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}