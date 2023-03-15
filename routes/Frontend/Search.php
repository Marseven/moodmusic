<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:35
 */
Route::get('search/song/{slug}', 'SearchController@song')->name('search.song');
Route::get('search/artist/{slug}', 'SearchController@artist')->name('search.artist');
Route::get('search/album/{slug}', 'SearchController@album')->name('search.album');
Route::get('search/playlist/{slug}', 'SearchController@playlist')->name('search.playlist');
Route::get('search/podcast/{slug}', 'SearchController@podcast')->name('search.podcast');
Route::get('search/user/{slug}', 'SearchController@user')->name('search.user');
Route::get('search/station/{slug}', 'SearchController@station')->name('search.station');
Route::get('search/event/{slug}', 'SearchController@event')->name('search.event');