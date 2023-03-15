<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:34
 */
Route::post('ad/get', 'AdController@get')->name('ad.get');
Route::post('ad/track', 'AdController@track')->name('ad.track');