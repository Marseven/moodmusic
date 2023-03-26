<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:34
 */
use Illuminate\Routing\Route;
Route::get('artist/{id}', 'ArtistController@index')->name('artist');
Route::get('artist/{id}/albums', 'ArtistController@albums')->name('artist.albums');
Route::get('artist/{id}/similar-artists', 'ArtistController@similar')->name('artist.similar');
Route::get('artist/{id}/followers', 'ArtistController@followers')->name('artist.followers');
Route::get('artist/{id}/events', 'ArtistController@events')->name('artist.events');
