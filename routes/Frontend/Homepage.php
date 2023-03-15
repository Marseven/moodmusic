<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:37
 */
Route::get('/', 'HomeController@index')->name('homepage');
Route::get('/sign-in', 'HomeController@index')->name('sign-in');
Route::get('/sign-up', 'HomeController@index')->name('sign-up');
