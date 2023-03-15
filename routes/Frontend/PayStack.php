<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 18:10
 */


Route::group(['middleware' => 'auth'], function () {
    Route::get('subscription/paystack/{id}', '\App\Modules\PayStack\Controller@subscriptionAuthorization')->name('paystack.subscription.authorization');
    Route::get('subscription/paystack/callback/{id}', '\App\Modules\PayStack\Controller@subscriptionCallback')->name('paystack.subscription.callback');

    Route::get('purchase/paystack/authorization', '\App\Modules\PayStack\Controller@purchaseAuthorization')->name('paystack.purchase.authorization');
    Route::get('purchase/paystack/callback', '\App\Modules\PayStack\Controller@purchaseCallback')->name('paystack.purchase.callback');
});