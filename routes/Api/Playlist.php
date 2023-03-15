<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:51
 */

Route::get('playlist/{id}', 'PlaylistController@index')->name('playlist');
Route::get('playlist/{id}/subscribers', 'PlaylistController@subscribers')->name('playlist.subscribers');
Route::get('playlist/{id}/collaborators', 'PlaylistController@collaborators')->name('playlist.collaborators');