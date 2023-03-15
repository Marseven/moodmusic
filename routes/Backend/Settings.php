<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:59
 */

Route::group(['middleware' => 'role:admin_settings'], function() {
    Route::get('settings', 'SettingsController@index')->name('settings');
    Route::post('settings', 'SettingsController@save')->name('services.save.post');
});