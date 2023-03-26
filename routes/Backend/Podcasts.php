<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:57
 */
use Illuminate\Routing\Route;
/*
 * Edit Radio
*/

Route::group(['middleware' => 'role:admin_radio'], function() {
    Route::get('podcasts', 'PodCastsController@index')->name('podcasts');
    Route::post('podcasts/import', 'PodCastsController@import')->name('podcasts.import');
    Route::get('podcasts/add', 'PodCastsController@add')->name('podcasts.add');
    Route::post('podcasts/add', 'PodCastsController@savePost')->name('podcasts.add.post');
    Route::get('podcasts/{id}/edit', 'PodCastsController@edit')->name('podcasts.edit');
    Route::post('podcasts/{id}/edit', 'PodCastsController@savePost')->name('podcasts.edit.post');
    Route::get('podcasts/{id}/delete', 'PodCastsController@delete')->name('podcasts.delete');
    Route::get('podcasts/{id}/episodes', 'PodCastsController@episodes')->name('podcasts.episodes');
    Route::post('podcasts/{id}/episodes', 'PodCastsController@episodesMassAction')->name('podcasts.episodes.mass.action');
    Route::get('podcasts/{id}/episodes/{eid}/edit', 'PodCastsController@episodeEdit')->name('podcasts.episodes.edit');
    Route::get('podcasts/{id}/episodes/{eid}/delete', 'PodCastsController@episodeDelete')->name('podcasts.episodes.delete');
    Route::get('podcasts/{id}/upload', 'PodCastsController@uploadEpisode')->name('podcasts.upload.episode');
    Route::post('podcasts/city-by-country-code', 'PodCastsController@cityByCountryCode')->name('podcasts.get.city');
    Route::post('podcasts/language-by-country-code', 'PodCastsController@languageByCountryCode')->name('podcasts.get.language');
});
