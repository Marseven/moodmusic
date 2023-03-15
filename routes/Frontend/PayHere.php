<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 18:10
 */


Route::group(['middleware' => 'auth'], function () {
    Route::get('subscription/payhere/{id}', '\App\Modules\PayHere\Controller@subscriptionAuthorization')->name('payhere.subscription.authorization');
    Route::get('subscription/payhere/callback/{id}', '\App\Modules\PayHere\Controller@subscriptionCallback')->name('payhere.subscription.callback');

    Route::get('purchase/payhere/authorization', '\App\Modules\PayHere\Controller@purchaseAuthorization')->name('payhere.purchase.authorization');
    Route::get('purchase/payhere/callback', '\App\Modules\PayHere\Controller@purchaseCallback')->name('payhere.purchase.callback');
});