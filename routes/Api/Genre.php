<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:52
 */

Route::get('genre/{slug}', 'GenreController@index')->name('genre');
Route::get('genre/{slug}/songs', 'GenreController@songs')->name('genre.songs');
Route::get('genre/{slug}/albums', 'GenreController@albums')->name('genre.albums');
Route::get('genre/{slug}/artists', 'GenreController@artists')->name('genre.artists');
Route::get('genre/{slug}/playlists', 'GenreController@playlists')->name('genre.playlists');
