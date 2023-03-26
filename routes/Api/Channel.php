<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:52
 */
use Illuminate\Routing\Route;
Route::get('channel/{slug}', 'ChannelController@index')->name('channel');
