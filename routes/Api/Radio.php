<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:52
 */

Route::get('radio', 'RadioController@index')->name('radio');
Route::get('radio/categories', 'RadioController@categories')->name('radio.categories');
Route::get('radio/category/{slug}', 'RadioController@browse')->name('radio.browse.category');
Route::get('radio/regions', 'RadioController@browse')->name('radio.browse.regions');
Route::get('radio/region/{slug}', 'RadioController@browse')->name('radio.browse.by.region');
Route::get('radio/languages', 'RadioController@browse')->name('radio.browse.languages');
Route::get('radio/language/{id}', 'RadioController@browse')->name('radio.browse.by.language');
Route::get('radio/countries', 'RadioController@browse')->name('radio.browse.countries');
Route::get('radio/country/{code}', 'RadioController@browse')->name('radio.browse.by.country');
Route::get('radio/city/{id}', 'RadioController@browse')->name('radio.browse.by.city');