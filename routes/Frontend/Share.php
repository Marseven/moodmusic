<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:35
 */
Route::get('share/embed/{theme}/{type}/{id}', 'ShareController@embed')->name('share.embed');