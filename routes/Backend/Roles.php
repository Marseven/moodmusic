<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 10:00
 */

Route::group(['middleware' => 'role:admin_roles'], function() {
    Route::get('roles', 'RolesController@index')->name('roles');
    Route::post('roles', 'RolesController@addPost')->name('roles.add');
    Route::get('roles/edit/{id}', 'RolesController@edit')->name('roles.edit');
    Route::post('roles/edit/{id}', 'RolesController@editPost')->name('roles.edit.post');
    Route::get('roles/delete/{id}', 'RolesController@delete')->name('roles.delete');
});