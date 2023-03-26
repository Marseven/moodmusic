<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:36
 */
use Illuminate\Support\Facades\Route;
Route::group(['middleware' => 'auth'], function () {
    Route::get('purchased/download/song/{id}/{format}', 'PurchasedDownloadController@song')->name('purchased.download.song');
});
