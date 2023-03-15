<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-07-22
 * Time: 18:12
 */

Route::group(['middleware' => 'role:admin_scheduled'], function() {
    Route::get('scheduling', 'SchedulingController@index')->name('scheduling-index');
    Route::post('scheduling/run', 'SchedulingController@runEvent')->name('scheduling-run');
});