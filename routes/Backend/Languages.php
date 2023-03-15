<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:58
 */


/*
 * Edit Album
*/
Route::group(['middleware' => 'role:admin_languages'], function() {
    Route::get('languages', 'LanguagesController@index')->name('languages');
    Route::get('languages/{language}/delete', 'LanguagesController@deleteLanguage')->name('languages.delete');
    Route::post('languages/create', 'LanguagesController@createLanguage')->name('languages.create');
    Route::get('languages/{language}/translations', 'LanguagesController@translations')->name('languages.translations');
    Route::post('languages/{language}/translations/create', 'LanguagesController@createTranslation')->name('languages.translations.create');
    Route::post('languages/{language}/translations', 'LanguagesController@updateTranslation')->name('languages.translations.update');
});