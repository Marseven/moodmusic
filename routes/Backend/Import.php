<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:56
 */


/*
 * Edit Song
*/

Route::group(['middleware' => 'role:admin_songs'], function() {
    Route::get('import', 'ImportController@index')->name('import');
    Route::post('import', 'ImportController@massAction')->name('import.mass.action');
    Route::get('import/edit/{id}', 'ImportController@edit')->name('import.edit');
    Route::post('import/edit/{id}', 'ImportController@editPost')->name('import.edit.post');
    Route::get('import/delete/{id}', 'ImportController@delete')->name('import.delete');
    Route::post('import/edit-title', 'ImportController@editTitlePost')->name('import.edit.title.post');
    Route::post('import/reject/{id}', 'ImportController@reject')->name('import.edit.reject.post');
});