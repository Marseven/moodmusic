<?php

namespace App\Models;

use App\Traits\SanitizedRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use SanitizedRequest;

    protected static function booted()
    {
        static::created(function ($subscription) {
            switch($subscription->service->plan_period_format) {
                case 'D':
                    $end_at = Carbon::now()->addDays($subscription->service->plan_period);
                    break;
                case 'W':
                    $end_at = Carbon::now()->addWeeks($subscription->service->plan_period);
                    break;
                case 'M':
                    $end_at = Carbon::now()->addMonths($subscription->service->plan_period);
                    break;
                case 'Y':
                    $end_at = Carbon::now()->addYears($subscription->service->plan_period);
                    break;
                default:
                    $end_at = Carbon::now()->addDays(1);
                    break;
            }

            RoleUser::updateOrCreate([
                'user_id' => $subscription->user->id,
            ], [
                'role_id' => $subscription->service->role_id,
                'end_at' => $end_at
            ]);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}