<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:57
 */

/*
 * Edit Radio
*/

Route::group(['middleware' => 'role:admin_radio'], function() {
    Route::get('cities', 'CitiesController@index')->name('cities');
    Route::post('cities', 'CitiesController@massAction')->name('cities.mass.action');
    Route::get('cities/add', 'CitiesController@add')->name('cities.add');
    Route::post('cities/add', 'CitiesController@addPost')->name('cities.add.post');
    Route::get('cities/{id}/edit', 'CitiesController@edit')->name('cities.edit');
    Route::post('cities/{id}/edit', 'CitiesController@editPost')->name('cities.edit.post');
    Route::get('cities/{id}/delete', 'CitiesController@delete')->name('cities.delete');
});