<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:33
 */
use Illuminate\Routing\Route;
Route::get('discover', 'DiscoverController@index')->name('discover');
