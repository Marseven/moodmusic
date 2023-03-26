<?php
/**
 * User profile page
 * Namespaces Frontend
 */
use Illuminate\Support\Facades\Route;
Route::get('queue', 'NowPlayingController@index')->name('queue');
