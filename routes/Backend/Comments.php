<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:56
 */


/*
 * Edit Song
*/

Route::group(['middleware' => 'role:admin_comments'], function() {
    Route::get('comments', 'CommentsController@index')->name('comments');
    Route::get('comments/approved', 'CommentsController@index')->name('comments.approved');
    Route::get('comments/edit/{id}', 'CommentsController@edit')->name('comments.edit');
    Route::post('comments/edit/{id}', 'CommentsController@editPost')->name('comments.edit.post');
    Route::get('comments/delete/{id}', 'CommentsController@delete')->name('comments.delete');
    Route::post('comments/approve', 'CommentsController@approve')->name('comments.approve');
});