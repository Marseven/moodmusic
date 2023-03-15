<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 18:10
 */


Route::group(['middleware' => 'api'], function () {
    Route::get('subscription/stripe/{id}', 'StripeController@subscriptionAuthorization')->name('stripe.subscription.authorization');
    Route::post('subscription/stripe/callback', 'StripeController@subscriptionCallback')->name('stripe.subscription.callback');

    Route::get('purchase/stripe/authorization', 'StripeController@purchaseAuthorization')->name('stripe.purchase.authorization');
    Route::post('purchase/stripe/callback', 'StripeController@purchaseCallback')->name('stripe.purchase.callback');
});