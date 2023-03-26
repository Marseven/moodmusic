<?php
/**
 * User profile page
 * Namespaces Frontend
 */
use Illuminate\Routing\Route;
Route::get('queue', 'NowPlayingController@index')->name('queue');
