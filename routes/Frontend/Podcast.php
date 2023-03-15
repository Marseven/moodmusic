<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:34
 */
Route::get('podcast/{id}/{slug}', 'PodcastController@index')->name('podcast');
Route::get('podcast/{id}/{slug}/subscribers', 'PodcastController@subscribers')->name('podcast.subscribers');
Route::get('podcast/{id}/{slug}/episode/{epid}', 'PodcastController@episode')->name('podcast.episode');

Route::get('podcast/episode/stream/{id}', 'PodcastStreamController@mp3')->name('podcast.episode.stream.mp3')->middleware('signed');
Route::get('podcast/episode/stream/hls/{id}', 'PodcastStreamController@hls')->name('podcast.episode.stream.hls');
