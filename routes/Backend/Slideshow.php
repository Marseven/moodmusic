<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-24
 * Time: 15:52
 */

Route::group(['middleware' => 'role:admin_slideshow'], function() {

    Route::get('slideshow', 'SlideshowController@index')->name('slideshow.overview');
    Route::get('slideshow/add', 'SlideshowController@add')->name('slideshow.add');
    Route::post('slideshow/add', 'SlideshowController@addPost')->name('slideshow.add.post');
    Route::get('slideshow/edit/{id}', 'SlideshowController@edit')->name('slideshow.edit');
    Route::post('slideshow/edit/{id}', 'SlideshowController@editPost')->name('slideshow.edit.post');
    Route::post('slideshow/sort', 'SlideshowController@sort')->name('slideshow.sort.post');

    Route::get('slideshow/home', 'SlideshowController@index')->name('slideshow.home');
    Route::get('slideshow/discover', 'SlideshowController@index')->name('slideshow.discover');
    Route::get('slideshow/radio', 'SlideshowController@index')->name('slideshow.radio');
    Route::get('slideshow/community', 'SlideshowController@index')->name('slideshow.community');
    Route::get('slideshow/trending', 'SlideshowController@index')->name('slideshow.trending');
    Route::get('slideshow/genre/{id}', 'SlideshowController@index')->name('slideshow.genre');
    Route::get('slideshow/mood/{id}', 'SlideshowController@index')->name('slideshow.mood');
    Route::get('slideshow/station-category/{id}', 'SlideshowController@index')->name('slideshow.station-category');
    Route::get('slideshow/podcast-category/{id}', 'SlideshowController@index')->name('slideshow.podcast-category');
    Route::get('slideshow/delete/{id}', 'SlideshowController@delete')->name('slideshow.delete');
});