<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:56
 */

/*
 * Edit Artist
*/
Route::group(['middleware' => 'role:admin_artist_claim'], function() {
    Route::get('artist-access', 'ArtistAccessController@index')->name('artist.access');
    Route::get('artist-access/edit/{id}', 'ArtistAccessController@edit')->name('artist.access.edit');
    Route::post('artist-access/edit/{id}', 'ArtistAccessController@editPost')->name('artist.access.edit.post');
    Route::get('artist-access/reject/{id}', 'ArtistAccessController@reject')->name('artist.access.reject');
});
