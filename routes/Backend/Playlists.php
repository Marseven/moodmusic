<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:57
 */

/*
 * Edit Playlist
*/
Route::group(['middleware' => 'role:admin_playlists'], function() {
    Route::get('playlists', 'PlaylistsController@index')->name('playlists');
    Route::post('playlists', 'PlaylistsController@massAction')->name('playlists.mass.action');
    Route::post('playlists/add', 'PlaylistsController@savePost')->name('playlists.add.post');
    Route::get('playlists/edit/{id}', 'PlaylistsController@edit')->name('playlists.edit');
    Route::post('playlists/edit/{id}', 'PlaylistsController@savePost')->name('playlists.edit.post');
    Route::get('playlists/track-list/{id}', 'PlaylistsController@trackList')->name('playlists.tracklist');
    Route::post('playlists/track-list/{id}', 'PlaylistsController@trackListMassAction')->name('playlists.tracklist.mass.action');
    Route::get('playlists/delete/{id}', 'PlaylistsController@delete')->name('playlists.delete');
});