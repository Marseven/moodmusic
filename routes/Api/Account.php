<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:34
 */

Route::get('reset-password/{token}', 'AccountController@resetPassword')->name('account.reset.password');
Route::post('reset-password', 'AccountController@setNewPassword')->name('account.set.new.password');
Route::get('verify/{code}', 'AccountController@verifyEmail')->name('account.verify');
