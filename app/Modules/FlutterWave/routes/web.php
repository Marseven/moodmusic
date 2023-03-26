<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 18:10
 */

Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    Route::group(['middleware' => 'auth'], function () {
        Route::get('subscription/flutterwave/{id}', '\App\Modules\FlutterWave\Controller@subscriptionAuthorization')->name('flutterwave.subscription.authorization');
        Route::get('subscription/flutterwave/callback/{id}', '\App\Modules\FlutterWave\Controller@subscriptionCallback')->name('flutterwave.subscription.callback');

        Route::get('purchase/flutterwave/authorization', '\App\Modules\FlutterWave\Controller@purchaseAuthorization')->name('flutterwave.purchase.authorization');
        Route::get('purchase/flutterwave/callback', '\App\Modules\FlutterWave\Controller@purchaseCallback')->name('flutterwave.purchase.callback');
    });
});