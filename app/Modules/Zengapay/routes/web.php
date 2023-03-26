<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 18:10
 */
Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    Route::group(['middleware' => 'auth'], function () {
        Route::get('subscription/zengapay/{id}', '\App\Modules\Zengapay\Controller@subscriptionAuthorization')->name('zengapay.subscription.authorization');
        Route::post('subscription/zengapay/callback/{id}', '\App\Modules\Zengapay\Controller@subscriptionCallback')->name('zengapay.subscription.callback');

        Route::get('purchase/zengapay/authorization', '\App\Modules\Zengapay\Controller@purchaseAuthorization')->name('zengapay.purchase.authorization');
        Route::post('purchase/zengapay/callback', '\App\Modules\Zengapay\Controller@purchaseCallback')->name('zengapay.purchase.callback');
    });
});