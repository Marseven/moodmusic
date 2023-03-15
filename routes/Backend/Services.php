<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 22:56
 */


Route::group(['middleware' => 'role:admin_subscriptions'], function() {
    Route::get('services', 'ServicesController@index')->name('services');
    Route::get('services/add', 'ServicesController@add')->name('services.add');
    Route::post('services/add', 'ServicesController@addPost')->name('services.add.post');
    Route::get('services/edit/{id}', 'ServicesController@edit')->name('services.edit');
    Route::post('services/edit/{id}', 'ServicesController@editPost')->name('services.edit.post');
    Route::get('services/delete/{id}', 'ServicesController@delete')->name('services.delete');
});