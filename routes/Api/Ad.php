<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:34
 */
use Illuminate\Routing\Route;
Route::post('ad/get', 'AdController@get')->name('ad.get');
