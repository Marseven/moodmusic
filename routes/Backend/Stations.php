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
    Route::get('stations', 'StationsController@index')->name('stations');
    Route::get('stations/add', 'StationsController@add')->name('stations.add');
    Route::post('stations/add', 'StationsController@savePost')->name('stations.add.post');
    Route::get('stations/{id}/edit', 'StationsController@edit')->name('stations.edit');
    Route::post('stations/{id}/edit', 'StationsController@savePost')->name('stations.edit.post');
    Route::get('stations/{id}/delete', 'StationsController@delete')->name('stations.delete');
    Route::post('stations/city-by-country-code', 'StationsController@cityByCountryCode')->name('stations.get.city');
    Route::post('stations/language-by-country-code', 'StationsController@languageByCountryCode')->name('stations.get.language');
});