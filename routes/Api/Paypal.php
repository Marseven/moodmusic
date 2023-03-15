<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 18:10
 */


Route::group(['middleware' => 'api'], function () {
    Route::get('subscription/paypal/{id}', 'PaypalController@subscription')->name('paypal.subscription');
    Route::get('subscription/paypal/success/{id}', 'PaypalController@success')->name('paypal.subscription.success');
    Route::get('subscription/paypal/cancel', 'PaypalController@cancel')->name('paypal.subscription.cancel');

    Route::get('purchase/paypal/authorization', 'PaypalController@purchase')->name('paypal.purchase');
    Route::get('purchase/paypal/authorization/success', 'PaypalController@successAuthorization')->name('paypal.purchase.authorization.success');
    Route::get('purchase/paypal/cancel', 'PaypalController@cancel')->name('paypal.purchase.authorization.cancel');
});