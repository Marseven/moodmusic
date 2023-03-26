<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:51
 */
use Illuminate\Support\Facades\Route;
Route::get('search/suggest', 'SearchController@suggest')->name('search.suggest');
Route::get('search/song', 'SearchController@song')->name('search.song');
Route::get('search/artist', 'SearchController@artist')->name('search.artist');
Route::get('search/album', 'SearchController@album')->name('search.album');
Route::get('search/playlist', 'SearchController@playlist')->name('search.playlist');
Route::get('search/user', 'SearchController@user')->name('search.user');
Route::get('search/station', 'SearchController@station')->name('search.station');
Route::get('search/podcast', 'SearchController@podcast')->name('search.podcast');
