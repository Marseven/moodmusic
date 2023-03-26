<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:34
 */
use Illuminate\Support\Facades\Route;
Route::get('hashtag/{slug}', 'HashTagController@index')->name('hashtag');
Route::get('hashtag/{slug}/latest', 'HashTagController@index')->name('hashtag.latest');

