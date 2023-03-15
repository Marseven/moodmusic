<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-24
 * Time: 15:52
 */

/**
 * All route names are prefixed with 'admin.'.
 */
Route::group(['middleware' => 'role:admin_access'], function() {
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::post('/check-for-update', 'DashboardController@checkForUpdate')->name('dashboard.check.for.update');
});




