<?php
/**
 * Created by NiNaCoder.
 * Date: 2012-06-23
 * Time: 14:12
 */

Route::group(['middleware' => 'auth'], function () {
    /**
     * Use ThrottleMiddleware to set limit the comments user can post per minute
     * If admin leave 0 user can post 100 comment per minute.
     */
    //Route::group(['middleware' => 'GrahamCampbell\Throttle\Http\Middleware\ThrottleMiddleware:' . config('settings.comment_flood') . ',1'], function () {
        Route::post('reaction/react', 'ReactionController@react')->name('reaction.react');
        Route::post('reaction/revoke', 'ReactionController@revoke')->name('reaction.react.revoke');

    //});
});
