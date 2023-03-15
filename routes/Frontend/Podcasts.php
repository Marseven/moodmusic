<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:33
 */
Route::get('podcasts', 'PodcastsController@index')->name('podcasts');
Route::get('podcasts/regions', 'PodcastsController@browse')->name('podcasts.browse.regions');
Route::get('podcasts/region/{slug}', 'PodcastsController@browse')->name('podcasts.browse.by.region');
Route::get('podcasts/languages', 'PodcastsController@browse')->name('podcasts.browse.languages');
Route::get('podcasts/language/{id}', 'PodcastsController@browse')->name('podcasts.browse.by.language');
Route::get('podcasts/countries', 'PodcastsController@browse')->name('podcasts.browse.countries');
Route::get('podcasts/country/{code}', 'PodcastsController@browse')->name('podcasts.browse.by.country');
Route::get('podcasts/category/{slug}', 'PodcastsController@browse')->name('podcasts.browse.category');
