<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:56
 */

/*
 * Edit User
*/
Route::group(['middleware' => 'role:admin_users'], function() {
    Route::get('users', 'UsersController@index')->name('users');
    Route::post('users', 'UsersController@massAction')->name('users.mass.action');
    Route::get('users/add', 'UsersController@add')->name('users.add');
    Route::post('users/add', 'UsersController@addPost')->name('users.add.post');
    Route::get('users/edit/{id}', 'UsersController@edit')->name('users.edit');
    Route::post('users/edit/{id}', 'UsersController@editPost')->name('users.edit.post');
    Route::get('users/delete/{id}', 'UsersController@delete')->name('users.delete');
});