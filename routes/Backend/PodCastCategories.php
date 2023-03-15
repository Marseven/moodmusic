<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:57
 */

/*
 * Edit Radio
*/

Route::group(['middleware' => 'role:admin_radio'], function() {
    Route::get('podcast-categories', 'PodcastCategoriesController@index')->name('podcast-categories');
    Route::get('podcast-categories/add', 'PodcastCategoriesController@add')->name('podcast-categories.add');
    Route::post('podcast-categories/add', 'PodcastCategoriesController@addPost')->name('podcast-categories.add.post');
    Route::get('podcast-categories/delete/{id}', 'PodcastCategoriesController@delete')->name('podcast-categories.delete');
    Route::get('podcast-categories/edit/{id}', 'PodcastCategoriesController@edit')->name('podcast-categories.edit');
    Route::post('podcast-categories/edit/{id}', 'PodcastCategoriesController@editPost')->name('podcast-categories.edit.post');
    Route::post('podcast-categories/sort', 'PodcastCategoriesController@sort')->name('podcast-categories.sort.post');
});