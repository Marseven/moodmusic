<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 10:00
 */
use Illuminate\Support\Facades\Route;
Route::group(['middleware' => 'role:admin_songs'], function() {
    Route::get('bulk', 'BulkController@index')->name('bulk');
});
