<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:35
 */
Route::post('language/switch', 'LanguageController@switchLanguage')->name('language.switch');
Route::post('language/current', 'LanguageController@currentLanguage')->name('language.current');