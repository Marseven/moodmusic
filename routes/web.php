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

include(__DIR__.'/../app/Helpers/Helper.php');

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::group(['middleware' => 'locale'], function() {

    /**
     * Backend Routes
     * Namespaces indicate folder structure
     */

    Route::group(['laroute' => false, 'prefix' => env('APP_ADMIN_PATH', 'admin'), 'namespace' => 'Backend', 'as' => 'backend.'], function() {
        Route::get('login', ['uses' => 'AdminAuthController@getLogin'])->name('login');
        Route::post('login', ['uses' => 'AdminAuthController@postLogin'])->name('login.post');
        Route::get('logout', ['uses' => 'AdminAuthController@getLogout'])->name('logout');
        Route::get('forgot-password', ['uses' => 'AdminAuthController@forgotPassword'])->name('forgot-password');
        Route::post('forgot-password', ['uses' => 'AdminAuthController@forgotPasswordPost'])->name('forgot-password.post');
        Route::post('aareset-password', ['uses' => 'AdminAuthController@resetPasswordPost'])->name('reset-password.post');
        Route::get('aareset-password/{token}', ['uses' => 'AdminAuthController@resetPassword'])->name('reset-password');
    });

    Route::group(['laroute' => false, 'middleware' => 'Admin', 'prefix' => env('APP_ADMIN_PATH', 'admin'), 'namespace' => 'Backend', 'as' => 'backend.'], function() {
        includeAdminModuleRouteFiles();
        includeRouteFiles(__DIR__.'/Backend/');
    });

    /* ----------------------------------------------------------------------- */

    /**
     * Frontend Routes
     * Namespaces indicate folder structure
     */


    /** Check if website is set to offline
     * If website is set to offline then check if user have permission to access the offline website or not
     * If no permission, display offline reason page
     * Set permission by use Usergroup and Roles in admin section
     */


    if(config('settings.site_offline'))
    {
        Route::group(['middleware' => 'auth'], function () {
            includeModuleRouteFiles();
            Route::group(['middleware' => 'role:allow_offline'], function() {
                Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
                    includeRouteFiles(__DIR__.'/Frontend/');
                });
            });
        });
    } else {
        includeModuleRouteFiles();
        Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
            includeRouteFiles(__DIR__.'/Frontend/');
        });
    }


    Auth::routes();
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
