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
    Route::get('radios', 'RadiosController@index')->name('radios');
    Route::get('radios/add', 'RadiosController@add')->name('radios.add');
    Route::post('radios/add', 'RadiosController@addPost')->name('radios.add.post');
    Route::get('radios/delete/{id}', 'RadiosController@delete')->name('radios.delete');
    Route::get('radios/edit/{id}', 'RadiosController@edit')->name('radios.edit');
    Route::post('radios/edit/{id}', 'RadiosController@editPost')->name('radios.edit.post');
    Route::post('radios/sort', 'RadiosController@sort')->name('radios.sort.post');
});