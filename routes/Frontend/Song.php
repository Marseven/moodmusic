<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:36
 */
Route::post('song/autoplay', 'SongController@autoplay')->name('song.autoplay.get');
Route::get('song/{id}/{slug}', 'SongController@index')->name('song');
Route::get('download/song/{id}', 'SongController@download')->name('song.download');
Route::get('download/song/{id}/hd', 'SongController@download')->name('song.download.hd');
Route::get('stream/{id}', 'StreamController@mp3')->name('song.stream.mp3')->middleware('signed');
Route::get('stream/{id}/hd', 'StreamController@hdMp3')->name('song.stream.mp3.hd')->middleware('signed');
Route::get('stream/hls/{id}', 'StreamController@hls')->name('song.stream.hls');
Route::get('stream/hls/{id}/hd', 'StreamController@hlsHD')->name('song.stream.hls.hd');
Route::get('stream/{id}/youtube', 'StreamController@youtube')->name('song.stream.youtube');
Route::get('waveform/{id}', 'StreamController@getWaveform')->name('waveform.get');
Route::post('waveform/save/{id}', 'StreamController@saveWaveform')->name('waveform.save');
Route::any('lyrics/{id}', 'LyricController@index')->name('lyrics.get');

Route::group(['middleware' => 'auth'], function () {
    Route::get('download/yt/{id}', 'SongController@downloadFromYT')->name('song.download.yt');
});

/**
 * Use ThrottleMiddleware to set limit the track stats user can post per minute
 * If admin leave 0 user can post 100 comment per minute.
 */
Route::group(['middleware' => 'GrahamCampbell\Throttle\Http\Middleware\ThrottleMiddleware:1000,1'], function () {
    Route::post('stream/on-track-played', 'StreamController@onTrackPlayed')->name('song.stream.track.played');
});
