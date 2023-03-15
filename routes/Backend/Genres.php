<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:56
 */


/*
 * Edit Genre
*/

Route::group(['middleware' => 'role:admin_genres'], function() {
    Route::get('genres', 'GenresController@index')->name('genres');
    Route::get('genres/add', 'GenresController@add')->name('genres.add');
    Route::post('genres/add', 'GenresController@addPost')->name('genres.add.post');
    Route::get('genres/edit/{id}', 'GenresController@edit')->name('genres.edit');
    Route::post('genres/edit/{id}', 'GenresController@editPost')->name('genres.edit.post');
    Route::get('genres/delete/{id}', 'GenresController@delete')->name('genres.delete');
    Route::post('genres/sort', 'GenresController@sort')->name('genres.sort.post');
});