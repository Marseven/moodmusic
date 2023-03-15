<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-07-22
 * Time: 03:53
 */

Route::group(['middleware' => 'role:admin_api_tester'], function() {
    Route::get('api-tester', 'ApiTesterController@index')->name('api-tester-index');
    Route::post('api-tester/handle', 'ApiTesterController@handle')->name('api-tester-handle');
});