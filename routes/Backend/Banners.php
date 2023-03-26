<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:58
 */

 use Illuminate\Routing\Route;
/*
 * Edit Album
*/
Route::group(['middleware' => 'role:admin_banners'], function() {
    Route::get('banners', 'BannersController@index')->name('banners');
    Route::get('banners/add', 'BannersController@add')->name('banners.add');
    Route::post('banners/add', 'BannersController@addPost')->name('banners.add.post');
    Route::get('banners/edit/{id}', 'BannersController@edit')->name('banners.edit');
    Route::post('banners/edit/{id}', 'BannersController@editPost')->name('banners.edit.post');
    Route::get('banners/delete/{id}', 'BannersController@delete')->name('banners.delete');
    Route::get('banners/disable/{id}', 'BannersController@disable')->name('banners.disable');
    Route::get('banners/reports', 'BannersController@reports')->name('banners.reports');
    Route::post('banners/reports', 'BannersController@reportByPeriod')->name('banners.reports.period');

});
