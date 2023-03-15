<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:35
 */

Route::get('blog', 'BlogController@index')->name('blog');
Route::get('blog/{category}', 'BlogController@index')->name('blog.category');
Route::get('blog/tags/{tag}', 'BlogController@index')->name('blog.tags');
Route::get('blog/{year}', 'BlogController@index')->where('year', '[0-9]{4}')->name('blog.browse.by.year');
Route::get('blog/{year}/{month}', 'BlogController@index')->where('year', '[0-9]{4}')->where('month', '[0-9]{2}')->name('blog.browse.by.month');
Route::get('blog/{year}/{month}/{day}', 'BlogController@index')->where('year', '[0-9]{4}')->where('month', '[0-9]{2}')->where('day', '([0-9]{2})')->name('blog.browse.by.day');
Route::get('post/{id}/{slug}', 'BlogController@show')->name('post');
Route::get('download/post/{id}/attachment/{attachment-id}', 'BlogController@downloadAttachment')->name('post.download.attachment');

