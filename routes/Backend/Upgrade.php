<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:58
 */
use Illuminate\Routing\Route;

/*
 * Edit Album
*/
Route::group(['middleware' => 'role:admin_terminal'], function() {
    Route::get('upgrade', 'UpgradeController@index')->name('upgrade');
    Route::post('process', 'UpgradeController@checkingLicense')->name('upgrade.process');
});
