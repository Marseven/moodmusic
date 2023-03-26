<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 18:10
 */
Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    Route::group(['middleware' => 'auth'], function () {
        Route::get('subscription/aim/{id}', '\App\Modules\AIM\Controller@subscriptionAuthorization')->name('aim.subscription.authorization');
        Route::get('subscription/aim/callback/{id}', '\App\Modules\AIM\Controller@subscriptionCallback')->name('aim.subscription.callback');

        Route::get('purchase/aim/authorization', '\App\Modules\AIM\Controller@purchaseAuthorization')->name('aim.purchase.authorization');
        Route::get('purchase/aim/callback', '\App\Modules\AIM\Controller@purchaseCallback')->name('aim.purchase.callback');
    });
});