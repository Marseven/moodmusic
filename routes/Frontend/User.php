<?php
/**
 * User profile page
 * Namespaces Frontend
 */

Route::get('{username}', 'ProfileController@index')->where('username', '[A-Za-z0-9]+')->name('user');
Route::get('{username}/feed', 'ProfileController@feed')->where('username','[A-Za-z0-9]+')->name('user.feed');
Route::get('{username}/posts/{id}', 'ProfileController@posts')->where('username','[A-Za-z0-9]+')->name('user.posts');
Route::get('{username}/collection', 'ProfileController@collection')->where('username','[A-Za-z0-9]+')->name('user.collection');
Route::get('{username}/favorites', 'ProfileController@favorites')->where('username','[A-Za-z0-9]+')->name('user.favorites');
Route::get('{username}/playlists', 'ProfileController@playlists')->where('username','[A-Za-z0-9]+')->name('user.playlists');
Route::get('{username}/playlists/subscribed', 'ProfileController@subscribed')->where('username','[A-Za-z0-9]+')->name('user.playlists.subscribed');
Route::get('{username}/followers', 'ProfileController@followers')->where('username','[A-Za-z0-9]+')->name('user.followers');
Route::get('{username}/following', 'ProfileController@following')->where('username','[A-Za-z0-9]+')->name('user.following');
Route::get('{username}/notifications', 'ProfileController@notifications')->where('username','[A-Za-z0-9]+')->name('user.notifications');
Route::get('{username}/purchased', 'ProfileController@purchased')->where('username','[A-Za-z0-9]+')->name('user.purchased');
Route::get('{username}/now-playing', 'ProfileController@now_playing')->where('username','[A-Za-z0-9]+')->name('user.now_playing');
Route::post('{username}/now-playing', 'ProfileController@now_playing')->where('username','[A-Za-z0-9]+')->name('user.now_playing.post');
