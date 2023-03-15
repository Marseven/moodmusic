<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 10:00
 */

Route::group(['middleware' => 'role:admin_sitemap'], function() {
    Route::get('sitemap', 'SitemapController@index')->name('sitemap');
    Route::post('sitemap', 'SitemapController@make')->name('sitemap.make');
});