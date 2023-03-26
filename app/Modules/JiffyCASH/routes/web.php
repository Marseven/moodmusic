<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 18:10
 */
Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    Route::group(['middleware' => 'auth'], function () {
        Route::get('subscription/jiffycash/{id}', '\App\Modules\JiffyCASH\Controller@subscriptionAuthorization')->name('jiffycash.subscription.authorization');
        Route::post('subscription/jiffycash/callback/{id}', '\App\Modules\JiffyCASH\Controller@subscriptionCallback')->name('jiffycash.subscription.callback');

        Route::get('purchase/jiffycash/authorization', '\App\Modules\JiffyCASH\Controller@purchaseAuthorization')->name('jiffycash.purchase.authorization');
        Route::post('purchase/jiffycash/callback', '\App\Modules\JiffyCASH\Controller@purchaseCallback')->name('jiffycash.purchase.callback');
    });
});