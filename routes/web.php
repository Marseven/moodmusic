<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\AppHomeController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\Search\SearchController;
use App\Http\Controllers\TrackController as TrackControllerAlias;
use Common\Auth\Controllers\UserController;

//FRONT-END ROUTES THAT NEED TO BE PRE-RENDERED
Route::get('/', [AppHomeController::class, 'show'])->middleware('prerenderIfCrawler');
Route::get('artist/{artist}', [ArtistController::class, 'show'])->middleware('prerenderIfCrawler');
Route::get('artist/{artist}/{name}', [ArtistController::class, 'show'])->middleware('prerenderIfCrawler');
Route::get('album/{album}/{artistName}/{albumName}', [AlbumController::class, 'show'])->middleware('prerenderIfCrawler');
Route::get('track/{track}', [TrackControllerAlias::class, 'show'])->middleware('prerenderIfCrawler');
Route::get('track/{track}/{name}', [TrackControllerAlias::class, 'show'])->middleware('prerenderIfCrawler');
Route::get('playlist/{id}', [PlaylistController::class, 'show'])->middleware('prerenderIfCrawler');
Route::get('playlist/{id}/{name}', [PlaylistController::class, 'show'])->middleware('prerenderIfCrawler');
Route::get('user/{id}', [UserController::class, 'show'])->middleware('prerenderIfCrawler');
Route::get('user/{id}/{name}', [UserController::class, 'show'])->middleware('prerenderIfCrawler');
Route::get('genre/{name}', [GenreController::class, 'show'])->middleware('prerenderIfCrawler');
Route::get('channel/{channel}', [ChannelController::class, 'show'])->middleware('prerenderIfCrawler');
Route::get('search/{query}', [SearchController::class, 'index'])->middleware('prerenderIfCrawler');
Route::get('search/{query}/{tab}', [SearchController::class, 'index'])->middleware('prerenderIfCrawler');

// REDIRECT LEGACY ROUTES
Route::get('genre/{name}', function($genre) {
    return redirect("channel/genre/$genre", 301);
});

Route::get('channels/{name}', function($name) {
    return redirect("channel/$name", 301);
});

//CATCH ALL ROUTES AND REDIRECT TO HOME
Route::get('{all}', [AppHomeController::class, 'show'])->where('all', '.*');
