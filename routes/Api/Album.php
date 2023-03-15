<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:34
 */
Route::get('album/{id}', 'AlbumController@index')->name('album');
Route::get('album/{id}/related-albums', 'AlbumController@related')->name('album.related');
