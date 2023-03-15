<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-06
 * Time: 13:50
 */

namespace App\Models;

use App\Traits\SanitizedRequest;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use SanitizedRequest;

    protected $appends = ['currency', 'currency_symbol'];

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function getCurrencyAttribute()
    {
        return config('settings.currency', 'USD');
    }

    public function getCurrencySymbolAttribute()
    {
        return __('symbol.' . config('settings.currency', 'USD'));
    }
}