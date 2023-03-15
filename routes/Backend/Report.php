<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-08
 * Time: 21:45
 */

Route::group(['middleware' => 'role:admin_subscriptions'], function() {
    Route::get('reports', 'ReportsController@index')->name('reports');
    Route::post('reports', 'ReportsController@post')->name('orders.post');
});