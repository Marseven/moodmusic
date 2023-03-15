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
    Route::get('countries', 'CountriesController@index')->name('countries');
    Route::post('countries', 'CountriesController@massAction')->name('countries.mass.action');
    Route::get('countries/add', 'CountriesController@add')->name('countries.add');
    Route::post('countries/add', 'CountriesController@savePost')->name('countries.add.post');
    Route::get('countries/{id}/edit', 'CountriesController@edit')->name('countries.edit');
    Route::post('countries/{id}/edit', 'CountriesController@savePost')->name('countries.edit.post');
    Route::get('countries/{id}/delete', 'CountriesController@delete')->name('countries.delete');
});