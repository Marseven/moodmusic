<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:51
 */

Route::get('episode/{epid}', 'PodcastController@episode')->name('podcast.episode');
Route::get('podcast/{id}', 'PodcastController@index')->name('podcast');
Route::get('podcast/{id}/subscribers', 'PodcastController@subscribers')->name('podcast.subscribers');
