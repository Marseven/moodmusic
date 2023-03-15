<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-08
 * Time: 12:49
 */

Route::group(['middleware' => 'auth'], function () {


    Route::get('artist-management', 'ArtistManagementController@index')->name('auth.user.artist.manager');
    Route::post('artist-management/withdraw', 'ArtistManagementController@withdraw')->name('auth.user.artist.withdraw');
    Route::get('artist-management/uploaded', 'ArtistManagementController@uploaded')->name('auth.user.artist.manager.uploaded');
    Route::get('artist-management/albums', 'ArtistManagementController@albums')->name('auth.user.artist.manager.albums');
    Route::post('artist-management/albums/create', 'ArtistManagementController@createAlbum')->name('auth.user.artist.manager.albums.create');
    Route::post('artist-management/albums/edit', 'ArtistManagementController@editAlbum')->name('auth.user.artist.manager.albums.edit');
    Route::post('artist-management/albums/delete', 'ArtistManagementController@deleteAlbum')->name('auth.user.artist.manager.albums.delete');
    Route::post('artist-management/albums/sort', 'ArtistManagementController@sortAlbumSongs')->name('auth.user.artist.manager.albums.sort');
    Route::get('artist-management/albums/{id}', 'ArtistManagementController@showAlbum')->name('auth.user.artist.manager.albums.show');
    Route::get('artist-management/albums/{id}/upload', 'ArtistManagementController@uploadAlbum')->name('auth.user.artist.manager.albums.upload');
    Route::post('artist-management/albums/{id}/upload', 'ArtistManagementController@handleUpload')->name('auth.user.artist.manager.albums.upload.post');


    Route::get('artist-management/podcasts', 'ArtistManagementController@podcasts')->name('auth.user.artist.manager.podcasts');
    Route::post('artist-management/podcasts/create', 'ArtistManagementController@createPodcast')->name('auth.user.artist.manager.podcasts.create');
    Route::post('artist-management/podcasts/import', 'ArtistManagementController@importPodcast')->name('auth.user.artist.manager.podcasts.import');
    Route::post('artist-management/podcasts/edit', 'ArtistManagementController@editPodcast')->name('auth.user.artist.manager.podcasts.edit');
    Route::post('artist-management/podcasts/delete', 'ArtistManagementController@deletePodcast')->name('auth.user.artist.manager.podcasts.delete');
    Route::post('artist-management/podcasts/sort', 'ArtistManagementController@sortPodcastEpisodes')->name('auth.user.artist.manager.podcasts.sort');
    Route::get('artist-management/podcasts/{id}', 'ArtistManagementController@showPodcast')->name('auth.user.artist.manager.podcasts.show');
    Route::get('artist-management/podcasts/{id}/upload', 'ArtistManagementController@uploadPodcast')->name('auth.user.artist.manager.podcasts.upload');
    Route::post('artist-management/podcasts/{id}/upload', 'ArtistManagementController@handlePodcastUpload')->name('auth.user.artist.manager.podcasts.upload.post');
    Route::post('artist-management/podcasts/episode/edit', 'ArtistManagementController@editEpisode')->name('auth.user.artist.manager.episode.edit.post');
    Route::post('artist-management/podcasts/episode/delete', 'ArtistManagementController@deleteEpisode')->name('auth.user.artist.manager.episode.delete');


    Route::get('artist-management/events', 'ArtistManagementController@events')->name('auth.user.artist.manager.events');
    Route::get('artist-management/profile', 'ArtistManagementController@profile')->name('auth.user.artist.manager.profile');
    Route::post('artist-management/song/edit', 'ArtistManagementController@editSongPost')->name('auth.user.artist.manager.song.edit.post');
    Route::post('artist-management/song/delete', 'ArtistManagementController@deleteSong')->name('auth.user.artist.manager.song.delete');
    Route::post('artist-management/event/create', 'ArtistManagementController@createEvent')->name('auth.user.artist.manager.event.create');
    Route::post('artist-management/event/edit', 'ArtistManagementController@editEvent')->name('auth.user.artist.manager.event.edit');
    Route::post('artist-management/event/delete', 'ArtistManagementController@deleteEvent')->name('auth.user.artist.manager.event.delete');

    Route::get('artist-management/profile', 'ArtistManagementController@profile')->name('auth.user.artist.manager.profile');
    Route::post('artist-management/profile', 'ArtistManagementController@saveProfile')->name('auth.user.artist.manager.profile.save');

    Route::post('artist-management/genres', 'ArtistManagementController@genres')->name('auth.user.artist.manager.genres');
    Route::post('artist-management/moods', 'ArtistManagementController@moods')->name('auth.user.artist.manager.moods');
    Route::post('artist-management/categories ', 'ArtistManagementController@categories')->name('auth.user.artist.manager.categories ');
    Route::post('artist-management/countries ', 'ArtistManagementController@countries')->name('auth.user.artist.manager.countries ');
    Route::post('artist-management/languages ', 'ArtistManagementController@languages')->name('auth.user.artist.manager.languages ');

    Route::post('artist-management/chart/overview', 'ArtistManagementController@artistChart')->name('auth.user.artist.manager.chart.overview');
    Route::post('artist-management/chart/song/{id}', 'ArtistManagementController@songChart')->name('auth.user.artist.manager.chart.song');
});