<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:56
 */

/*
 * Edit Artist
*/
Route::group(['middleware' => 'role:admin_artists'], function() {
    Route::get('artists', 'ArtistsController@index')->name('artists');
    Route::post('artists', 'ArtistsController@massAction')->name('artists.mass.action');
    Route::get('artists/add', 'ArtistsController@add')->name('artists.add');
    Route::post('artists/add', 'ArtistsController@addPost')->name('artists.add.post');
    Route::get('artists/edit/{id}', 'ArtistsController@edit')->name('artists.edit');
    Route::post('artists/edit/{id}', 'ArtistsController@editPost')->name('artists.edit.post');
    Route::get('artists/delete/{id}', 'ArtistsController@delete')->name('artists.delete');
    Route::get('artists/upload/{id}', 'ArtistsController@upload')->name('artists.upload');

});
