<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:56
 */


/*
 * Edit Genre
*/

Route::group(['middleware' => 'role:admin_categories'], function() {
    Route::get('categories', 'CategoriesController@index')->name('categories');
    Route::post('categories', 'CategoriesController@cartSort')->name('categories.sort.post');
    Route::get('categories/add', 'CategoriesController@add')->name('categories.add');
    Route::post('categories/add', 'CategoriesController@addPost')->name('categories.add.post');
    Route::get('categories/edit/{id}', 'CategoriesController@edit')->name('categories.edit');
    Route::post('categories/edit/{id}', 'CategoriesController@editPost')->name('categories.edit.post');
    Route::get('categories/delete/{id}', 'CategoriesController@delete')->name('categories.delete');

});