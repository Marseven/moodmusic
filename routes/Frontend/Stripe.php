<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 18:10
 */


Route::group(['middleware' => 'auth'], function () {
    Route::post('subscription/stripe/callback', 'StripeController@subscriptionCallback')->name('stripe.subscription.callback');
    Route::post('purchase/stripe/callback', 'StripeController@purchaseCallback')->name('stripe.purchase.callback');
});