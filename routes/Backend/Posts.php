<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:57
 */

Route::group(['middleware' => 'role:admin_posts'], function() {
    Route::get('posts', 'PostsController@index')->name('posts');
    Route::post('posts', 'PostsController@massAction')->name('posts.mass.action');
    Route::get('posts/add', 'PostsController@add')->name('posts.add');
    Route::post('posts/add', 'PostsController@addPost')->name('posts.add.post');
    Route::get('posts/edit/{id}', 'PostsController@edit')->name('posts.edit');
    Route::post('posts/edit/{id}', 'PostsController@editPost')->name('posts.edit.post');
    Route::get('posts/delete/{id}', 'PostsController@delete')->name('posts.delete');

    Route::post('posts/media/get', 'PostsController@getMedia')->name('posts.get.media');
    Route::post('posts/media/delete', 'PostsController@deleteMedia')->name('posts.delete.media');
    Route::post('posts/media/download', 'PostsController@downloadMedia')->name('posts.download.media');
    Route::post('posts/media/upload', 'PostsController@uploadMedia')->name('posts.upload.media');

    Route::get('posts/media', 'PostsController@media')->name('post-media-index');
    Route::get('posts/media/{id}', 'PostsController@media')->name('post-media-index.associated');

});