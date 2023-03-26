<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:34
 */
use Illuminate\Support\Facades\Route;
Route::get('discover/genre/{slug}', 'GenreController@index')->name('genre');
