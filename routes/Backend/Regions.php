<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:57
 */

/*
 * Edit Region
*/

Route::group(['middleware' => 'role:admin_radio'], function() {
    Route::get('regions', 'RegionsController@index')->name('regions');
    Route::get('regions/add', 'RegionsController@add')->name('regions.add');
    Route::post('regions/add', 'RegionsController@savePost')->name('regions.add.post');
    Route::get('regions/{id}/edit', 'RegionsController@edit')->name('regions.edit');
    Route::post('regions/{id}/edit', 'RegionsController@savePost')->name('regions.edit.post');
    Route::get('regions/{id}/delete', 'RegionsController@delete')->name('regions.delete');
});