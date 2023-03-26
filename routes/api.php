<?php

include(__DIR__.'/../app/Helpers/Helper.php');

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['laroute' => false, 'namespace' => 'App\Http\Controllers\Frontend', 'as' => 'api.'], function () {
    Route::post('auth/userInfoValidate', 'AuthController@userInfoValidate')->name('auth.info.validate');
    Route::post('auth/usernameValidate', 'AuthController@usernameValidate')->name('auth.info.validate.username');
    Route::post('auth/signup', 'AuthController@signup')->name('auth.signup');
    Route::post('auth/login', 'AuthController@login')->name('auth.login');
    Route::get('connect/redirect/{service}', 'AuthController@socialiteLogin')->name('auth.login.socialite.redirect');
    Route::any('connect/callback/{service}', 'AuthController@socialiteLogin')->name('auth.login.socialite.callback');
});


Route::group(['laroute' => false, 'namespace' => 'App\Http\Controllers\Frontend', 'as' => 'api.', 'middleware' => 'auth:api'], function () {
    Route::get('settings', 'SettingsController@profile')->name('settings');
    Route::get('settings/subscription', 'SettingsController@subscription')->name('settings.subscription');
    Route::get('settings/account', 'SettingsController@account')->name('settings.account');
    Route::get('settings/password', 'SettingsController@password')->name('settings.password');
    Route::get('settings/preferences', 'SettingsController@preferences')->name('settings.preferences');
    Route::get('settings/services', 'SettingsController@services')->name('settings.services');
    Route::get('settings/devices', 'SettingsController@devices')->name('settings.devices');

    Route::post('auth/user', 'AuthController@user')->name('auth.user');
    Route::post('auth/user/settings/profile', 'AuthController@settingsProfile')->name('auth.user.settings.profile');
    Route::post('auth/user/settings/account', 'AuthController@settingsAccount')->name('auth.user.settings.account');
    Route::post('auth/user/settings/password', 'AuthController@settingsPassword')->name('auth.user.settings/password');
    Route::post('auth/user/settings/preferences', 'AuthController@settingsPreferences')->name('auth.user/settings/preferences');
    Route::post('auth/user/notifications', 'AuthController@notifications')->name('auth.user.notifications');
    Route::post('auth/user/notification-count', 'AuthController@notificationCount')->name('auth.user.notification.count');
    Route::post('auth/user/favorite', 'AuthController@favorite')->name('auth.user.favorite');
    Route::post('auth/user/song/favorite', 'AuthController@songFavorite')->name('auth.user.song.favorite');
    Route::post('auth/user/dob/update', 'AuthController@dobUpdate')->name('auth.user.dob.update');

    Route::post('auth/user/library', 'AuthController@library')->name('auth.user.library');
    Route::post('auth/user/song/library', 'AuthController@songLibrary')->name('auth.user.song.library');

    Route::post('auth/user/playlists', 'AuthController@playlists')->name('auth.user.playlists');
    Route::post('auth/user/playlists/subscribed', 'AuthController@subscribed')->name('auth.user.playlists.subscribed');
    Route::post('auth/user/playlist/delete', 'AuthController@deletePlaylist')->name('auth.user.playlist.delete');
    Route::post('auth/user/playlist/edit', 'AuthController@editPlaylist')->name('auth.user.playlist.edit');
    Route::post('auth/user/playlist/collaboration/set', 'AuthController@setPlaylistCollaboration')->name('auth.user.playlist.collaboration.set');
    Route::post('auth/user/playlist/collaboration/invite', 'AuthController@collaborativePlaylist')->name('auth.user.playlist.collaboration.invite');
    Route::post('auth/user/playlist/collaboration/accept', 'AuthController@collaborativePlaylist')->name('auth.user.playlist.collaboration.accept');
    Route::post('auth/user/playlist/collaboration/cancel', 'AuthController@collaborativePlaylist')->name('auth.user.playlist.collaboration.cancel');
    Route::post('auth/user/createPlaylist', 'AuthController@createPlaylist')->name('auth.user.create.playlist');
    Route::post('auth/user/addToPlaylist', 'AuthController@addToPlaylist')->name('auth.user.playlist.add.item');
    Route::post('auth/user/removeFromPlaylist', 'AuthController@removeFromPlaylist')->name('auth.user.playlist.remove.item');
    Route::post('auth/user/managePlaylist', 'AuthController@managePlaylist')->name('auth.user.playlist.manage');
    Route::post('auth/user/removeActivity', 'AuthController@removeActivity')->name('auth.user.removeActivity');
    Route::post('auth/user/artistClaim', 'AuthController@artistClaim')->name('auth.user.artistClaim');
    Route::post('auth/user/checkRole', 'AuthController@checkRole')->name('auth.user.role');
    Route::post('auth/user/subscription/cancel', 'AuthController@cancelSubscription')->name('auth.user.cancel.subscription');
    Route::post('auth/user/get-mention', 'AuthController@getMention')->name('auth.user.get.mention');
    Route::post('auth/user/get-hashtag', 'AuthController@getHashTag')->name('auth.user.get.hashtag');
    Route::post('auth/user/post-feed', 'AuthController@postFeed')->name('auth.user.post.feed');
    Route::post('auth/user/report', 'AuthController@report')->name('auth.user.report');

    Route::get('upload', 'UploadController@index')->name('auth.upload');
    Route::post('upload', 'UploadController@upload')->name('auth.upload.post');
    Route::get('auth/logout', 'AuthController@logout')->name('auth.logout');
});
if(app('request')->header('authorization')) {
    Route::group(['laroute' => true, 'namespace' => 'App\Http\Controllers\Frontend', 'as' => 'api.', 'middleware' => 'auth:api'], function () {
        includeRouteFiles(__DIR__.'/Api/');
        includeModuleAPIRouteFiles();
    });
} else {
    Route::group(['laroute' => true, 'namespace' => 'App\Http\Controllers\Frontend', 'as' => 'api.'], function () {
        includeRouteFiles(__DIR__.'/Api/');
        includeModuleAPIRouteFiles();
    });
}
