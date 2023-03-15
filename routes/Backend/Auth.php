<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-18
 * Time: 13:09
 */

Route::group(['middleware' => 'role:admin_songs'], function() {
    Route::post('auth/upload/bulk', 'AdminAuthController@upload')->name('upload.bulk');
    Route::post('auth/artist/{artistId}/upload', 'AdminAuthController@upload')->name('artist.upload.bulk');
    Route::post('auth/album/{album_id}/upload', 'AdminAuthController@upload')->name('album.upload.bulk');
    Route::post('auth/podcast/{podcast_id}/upload', 'AdminAuthController@uploadEpisode')->name('podcast.upload.bulk');
    Route::post('auth/song', 'AdminAuthController@editSong')->name('ajax.song.edit');
    Route::post('auth/podcast/episode', 'AdminAuthController@editEpisode')->name('ajax.podcast.episode.edit');
    Route::post('auth/addSong', 'AdminAuthController@addSong')->name('ajax.add.song');
    Route::post('auth/removeSong', 'AdminAuthController@removeSong')->name('ajax.remove.song');
});