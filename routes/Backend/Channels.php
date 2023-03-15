<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:59
 */

Route::group(['middleware' => 'role:admin_channels'], function() {
    Route::get('channels', 'ChannelsController@index')->name('channels.overview');
    Route::get('channels/add', 'ChannelsController@add')->name('channels.add');
    Route::post('channels/add', 'ChannelsController@addPost')->name('channels.add.post');
    Route::get('channels/edit/{id}', 'ChannelsController@edit')->name('channels.edit');
    Route::post('channels/edit/{id}', 'ChannelsController@editPost')->name('channels.edit.post');
    Route::post('channels/sort', 'ChannelsController@sort')->name('channels.sort.post');

    Route::get('channels/home', 'ChannelsController@index')->name('channels.home');
    Route::get('channels/discover', 'ChannelsController@index')->name('channels.discover');
    Route::get('channels/radio', 'ChannelsController@index')->name('channels.radio');
    Route::get('channels/community', 'ChannelsController@index')->name('channels.community');
    Route::get('channels/trending', 'ChannelsController@index')->name('channels.trending');
    Route::get('channels/genre/{id}', 'ChannelsController@index')->name('channels.genre');
    Route::get('channels/mood/{id}', 'ChannelsController@index')->name('channels.mood');
    Route::get('channels/station-category/{id}', 'ChannelsController@index')->name('channels.station-category');
    Route::get('channels/podcast-category/{id}', 'ChannelsController@index')->name('channels.podcast-category');
    Route::get('channels/delete/{id}', 'ChannelsController@delete')->name('channels.delete');
});