<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:35
 */
use Illuminate\Routing\Route;
Route::post('language/switch', 'LanguageController@switchLanguage')->name('language.switch');
Route::post('language/current', 'LanguageController@currentLanguage')->name('language.current');
