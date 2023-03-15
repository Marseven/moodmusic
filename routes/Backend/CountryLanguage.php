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
    Route::get('country-languages', 'CountryLanguagesController@index')->name('country.languages');
    Route::post('country-languages', 'CountryLanguagesController@massAction')->name('country.languages.mass.action');
    Route::get('country-languages/add', 'CountryLanguagesController@add')->name('country.languages.add');
    Route::post('country-languages/add', 'CountryLanguagesController@savePost')->name('country.languages.add.post');
    Route::get('country-languages/{id}/edit', 'CountryLanguagesController@edit')->name('country.languages.edit');
    Route::post('country-languages/{id}/edit', 'CountryLanguagesController@savePost')->name('country.languages.edit.post');
    Route::get('country-languages/{id}/delete', 'CountryLanguagesController@delete')->name('country.languages.delete');
});