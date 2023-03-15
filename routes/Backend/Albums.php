<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:58
 */


/*
 * Edit Album
*/
Route::group(['middleware' => 'role:admin_albums'], function() {
    Route::get('albums', 'AlbumsController@index')->name('albums');
    Route::post('albums', 'AlbumsController@massAction')->name('albums.mass.action');
    Route::get('albums/add', 'AlbumsController@add')->name('albums.add');
    Route::post('albums/add', 'AlbumsController@addPost')->name('albums.add.post');
    Route::get('albums/edit/{id}', 'AlbumsController@edit')->name('albums.edit');
    Route::post('albums/edit/{id}', 'AlbumsController@editPost')->name('albums.edit.post');
    Route::get('albums/track-list/{id}', 'AlbumsController@trackList')->name('albums.tracklist');
    Route::post('albums/track-list/{id}', 'AlbumsController@trackListMassAction')->name('albums.tracklist.mass.action');
    Route::get('albums/delete/{id}', 'AlbumsController@delete')->name('albums.delete');
    Route::get('albums/upload/{id}', 'AlbumsController@upload')->name('albums.upload');
    Route::post('albums/reject/{id}', 'AlbumsController@reject')->name('albums.edit.reject.post');
});