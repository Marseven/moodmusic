<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:35
 */

Route::get('store', 'StoreController@index')->name('store');
Route::get('store/filter/genres', 'StoreController@genres')->name('store.filter.genres');
Route::get('store/filter/moods', 'StoreController@moods')->name('store.filter.moods');
Route::get('store/filter/artists', 'StoreController@artists')->name('store.filter.artists');


