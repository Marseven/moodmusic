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
    Route::get('songs', 'SongsController@index')->name('songs');
    Route::post('songs', 'SongsController@massAction')->name('songs.mass.action');
    Route::get('songs/add', 'SongsController@add')->name('songs.add');
    Route::post('songs/add', 'SongsController@addPost')->name('songs.add.post');
    Route::get('songs/edit/{id}', 'SongsController@edit')->name('songs.edit');
    Route::post('songs/edit/{id}', 'SongsController@editPost')->name('songs.edit.post');
    Route::get('songs/delete/{id}', 'SongsController@delete')->name('songs.delete');
    Route::post('songs/edit-title', 'SongsController@editTitlePost')->name('songs.edit.title.post');
    Route::post('songs/reject/{id}', 'SongsController@reject')->name('songs.edit.reject.post');
});