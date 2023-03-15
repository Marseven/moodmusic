<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:53
 */

Route::get('song/{id}', 'SongController@index')->name('song');
Route::get('songs/{ids}', 'SongController@songFromIds')->name('songs.by.ids');
Route::post('song/autoplay', 'SongController@autoplay')->name('song.autoplay.get');
Route::get('song/download/offline/{id}', 'SongController@downloadOffline')->name('song.offline.download');
Route::get('song/download/offline/{id}/hd', 'SongController@downloadOffline')->name('song.offline.download.hd');
Route::get('stream/{id}', 'StreamController@mp3')->name('song.stream.mp3');
Route::get('stream/hls/{id}', 'StreamController@hls')->name('song.stream.hls');
Route::get('stream/hls/{id}/hd', 'StreamController@hlsHD')->name('song.stream.hls.hd');
Route::get('stream/{id}/youtube', 'StreamController@youtube')->name('song.stream.youtube');
Route::post('stream/on-track-played/{id}', 'StreamController@onTrackPlayed')->name('song.stream.track.played');

