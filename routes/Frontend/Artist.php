<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:34
 */

Route::get('artist/{id}/{slug}', 'ArtistController@index')->name('artist');
Route::get('artist/{id}/{slug}/albums', 'ArtistController@albums')->name('artist.albums');
Route::get('artist/{id}/{slug}/podcasts', 'ArtistController@podcasts')->name('artist.podcasts');
Route::get('artist/{id}/{slug}/similar-artists', 'ArtistController@similar')->name('artist.similar');
Route::get('artist/{id}/{slug}/followers', 'ArtistController@followers')->name('artist.followers');
Route::get('artist/{id}/{slug}/events', 'ArtistController@events')->name('artist.events');
Route::get('artist-artwork/spotify/{id}', 'ArtistController@spotifyArtwork')->name('artist.spotify.artwork');
