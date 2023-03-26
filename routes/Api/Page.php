<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:53
 */
use Illuminate\Routing\Route;
Route::get('page/{id}', 'PageController@index')->name('page');
