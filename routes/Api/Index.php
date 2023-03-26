<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:54
 */
use Illuminate\Routing\Route;
Route::get('homepage', 'HomeController@index')->name('homepage');
Route::get('community', 'CommunityController@index')->name('community');
Route::get('discover', 'DiscoverController@index')->name('discover');
Route::get('trending', 'TrendingController@index')->name('trending');
