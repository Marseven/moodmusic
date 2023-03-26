<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:35
 */
use Illuminate\Routing\Route;
Route::get('station/{id}', 'StationController@index')->name('station');
