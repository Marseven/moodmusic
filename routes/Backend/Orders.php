<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-06
 * Time: 23:13
 */
use Illuminate\Routing\Route;
Route::group(['middleware' => 'role:admin_subscriptions'], function() {
    Route::get('orders', 'OrdersController@index')->name('orders');
});
