<?php

namespace App\Models;

use App\Traits\SanitizedRequest;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use SanitizedRequest;

    protected $casts = [
        'started_at' => 'datetime:m/d/Y',
    ];

    public function delete()
    {
        Activity::where('activityable_type', $this->getMorphClass())->where('activityable_id', $this->id)->delete();
        Notification::where('notificationable_type', $this->getMorphClass())->where('notificationable_id', $this->id)->delete();
        return parent::delete();;
    }
}