<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:56
 */

/*
 * Edit User
*/

Route::get('profile', 'ProfileController@index')->name('profile');
Route::post('profile', 'ProfileController@editPost')->name('profile.edit.post');

