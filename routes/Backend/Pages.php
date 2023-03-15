<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:58
 */

/*
 * Edit Static Module
*/

Route::group(['middleware' => 'role:admin_pages'], function() {
    Route::get('pages', 'PagesController@index')->name('pages');
    Route::get('pages/add', 'PagesController@add')->name('pages.add');
    Route::post('pages/add ', 'PagesController@addPost')->name('pages.add.post');
    Route::get('pages/edit/{id}', 'PagesController@edit')->name('pages.edit');
    Route::post('pages/edit/{id}', 'PagesController@editPost')->name('pages.edit.post');
    Route::get('pages/delete/{id}', 'PagesController@delete')->name('pages.delete');
});