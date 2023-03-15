<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:33
 */
Route::get('radio', 'RadioController@index')->name('radio');
Route::post('radio/data/city-by-country-code', 'RadioController@cityByCountryCode')->name('radio.get.city');
Route::post('radio/data/language-by-country-code', 'RadioController@languageByCountryCode')->name('radio.get.language');

Route::get('radio/regions', 'RadioController@browse')->name('radio.browse.regions');
Route::get('radio/region/{slug}', 'RadioController@browse')->name('radio.browse.by.region');
Route::get('radio/languages', 'RadioController@browse')->name('radio.browse.languages');
Route::get('radio/language/{id}', 'RadioController@browse')->name('radio.browse.by.language');
Route::get('radio/countries', 'RadioController@browse')->name('radio.browse.countries');
Route::get('radio/country/{code}', 'RadioController@browse')->name('radio.browse.by.country');
Route::get('radio/city/{id}', 'RadioController@browse')->name('radio.browse.by.city');

Route::get('radio/category/{slug}', 'RadioController@browse')->name('radio.browse.category');
