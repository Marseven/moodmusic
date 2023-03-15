<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:33
 */
Route::get('trending', 'TrendingController@index')->name('trending');
Route::get('trending/week', 'TrendingController@index')->name('trending.week');
Route::get('trending/month', 'TrendingController@index')->name('trending.month');
