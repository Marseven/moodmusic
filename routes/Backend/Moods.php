<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-07-06
 * Time: 22:27
 */

/*
 * Edit Mood
*/

Route::group(['middleware' => 'role:admin_moods'], function() {
    Route::get('moods', 'MoodsController@index')->name('moods');
    Route::get('moods/add', 'MoodsController@add')->name('moods.add');
    Route::post('moods/add', 'MoodsController@addPost')->name('moods.add.post');
    Route::get('moods/edit/{id}', 'MoodsController@edit')->name('moods.edit');
    Route::post('moods/edit/{id}', 'MoodsController@editPost')->name('moods.edit.post');
    Route::get('moods/delete/{id}', 'MoodsController@delete')->name('moods.delete');
    Route::post('moods/sort', 'MoodsController@sort')->name('moods.sort.post');
});