<?php

namespace App\Models;

use App\Traits\SanitizedRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use SanitizedRequest;

    protected static function booted()
    {
        static::created(function ($order) {
            $model = (new $order->orderable_type)->findOrFail($order->orderable_id);
            if($model->user_id) {
                $commission = (intval(Role::getUserValue('monetization_sale_cut', $model->user_id)) * $order->amount) / 100;
                $model->user()->increment('balance', $commission);
                $order->commission = $commission;
                $order->save();

                if($order->orderable_type == 'App\Models\Song') {
                    $model->increment('sale_impression', $commission);
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getObjectAttribute()
    {
        return (new $this->orderable_type)::find($this->orderable_id);
    }
}