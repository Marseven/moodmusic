<?php
/**
 * User profile page
 * Namespaces Frontend
 */
use Illuminate\Support\Facades\Route;
Route::get('user/{id}', 'ProfileController@index')->where('id', '[0-9]+')->name('user');
Route::get('user/{id}/recent', 'ProfileController@recent')->where('id', '[0-9]+')->name('user.recent');
Route::get('user/{id}/feed', 'ProfileController@feed')->where('id', '[0-9]+')->name('user.feed');
Route::get('user/{id}/posts/{postId}', 'ProfileController@posts')->where('id', '[0-9]+')->name('user.posts');
Route::get('user/{id}/collection', 'ProfileController@collection')->where('id', '[0-9]+')->name('user.collection');
Route::get('user/{id}/favorites', 'ProfileController@favorites')->where('id', '[0-9]+')->name('user.favorites');
Route::get('user/{id}/playlists', 'ProfileController@playlists')->where('id', '[0-9]+')->name('user.playlists');
Route::get('user/{id}/playlists/subscribed', 'ProfileController@subscribed')->where('id', '[0-9]+')->name('user.playlists.subscribed');
Route::get('user/{id}/followers', 'ProfileController@followers')->where('id', '[0-9]+')->name('user.followers');
Route::get('user/{id}/following', 'ProfileController@following')->where('id', '[0-9]+')->name('user.following');
Route::get('user/{id}/notifications', 'ProfileController@notifications')->where('id', '[0-9]+')->name('user.notifications');
Route::get('user/{id}/now-playing', 'ProfileController@now_playing')->where('id', '[0-9]+')->name('user.now_playing');
Route::get('user/{id}/now-playing', 'ProfileController@now_playing')->where('id', '[0-9]+')->name('user.now_playing.post');
