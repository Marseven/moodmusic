<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:35
 */
Route::get('playlist/{id}/{slug}', 'PlaylistController@index')->name('playlist');
Route::get('playlist/{id}/{slug}/subscribers', 'PlaylistController@subscribers')->name('playlist.subscribers');
Route::get('playlist/{id}/{slug}/collaborators', 'PlaylistController@collaborators')->name('playlist.collaborators');