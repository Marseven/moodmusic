<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-07-22
 * Time: 00:27
 */

Route::group(['middleware' => 'role:admin_system_logs'], function() {
    Route::get('logs', 'LogController@index')->name('log-viewer-index');
    Route::get('logs/{file}', 'LogController@index')->name('log-viewer-file');
    Route::get('logs/{file}/tail', 'LogController@tail')->name('log-viewer-tail');
});