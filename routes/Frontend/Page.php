<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-14
 * Time: 09:21
 */

Route::get('page/{slug}', 'PageController@index')->name('page');