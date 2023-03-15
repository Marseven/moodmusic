<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 18:10
 */

Route::post('comments/get', 'CommentsController@getComments')->name('comments.get');
Route::post('comments/replies/get', 'CommentsController@getReplies')->name('comments.get.replies');

Route::group(['middleware' => 'auth'], function () {
    /**
     * Use ThrottleMiddleware to set limit the comments user can post per minute
     * If admin leave 0 user can post 100 comment per minute.
     */
    Route::post('comments/comment/edit', 'CommentsController@editComment')->name('comments.edit.comment');
    Route::post('comments/comment/edit/save', 'CommentsController@saveComment')->name('comments.save.comment');
    Route::group(['middleware' => 'GrahamCampbell\Throttle\Http\Middleware\ThrottleMiddleware:' . config('settings.comment_flood') . ',1'], function () {
        Route::post('comments/add', 'CommentsController@add')->name('comments.add');
        Route::post('comments/add/reply', 'CommentsController@reply')->name('comments.reply');
    });
});
