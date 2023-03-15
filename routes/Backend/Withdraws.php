<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-06
 * Time: 23:13
 */

Route::group(['middleware' => 'role:admin_earnings'], function() {
    Route::get('withdraws', 'WithdrawsController@index')->name('withdraws');
    Route::post('withdraws', 'WithdrawsController@process')->name('withdraws.process');
});