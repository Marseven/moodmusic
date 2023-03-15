<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:34
 */

Route::post('reset-password/send', 'AccountController@sendResetPassword')->name('account.send.request.reset.password');
Route::get('reset-password/{token}', 'AccountController@resetPassword')->name('account.reset.password');
Route::post('reset-password/new-password', 'AccountController@setNewPassword')->name('account.set.new.password');
Route::get('email-verify/{code}', 'AccountController@verifyEmail')->name('account.verify');
