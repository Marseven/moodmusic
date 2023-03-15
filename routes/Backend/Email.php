<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 10:00
 */

Route::group(['middleware' => 'role:admin_email'], function() {
    Route::get('email', 'EmailController@index')->name('email');
    Route::get('email/edit/{id}', 'EmailController@edit')->name('email.edit');
    Route::post('email/edit/{id}', 'EmailController@editPost')->name('email.edit.post');
    Route::get('email/delete/{id}', 'EmailController@delete')->name('email.delete');

});
