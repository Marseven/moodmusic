<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 18:10
 */
Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    Route::group(['middleware' => 'auth'], function () {
        Route::get('subscription/mpesa/{id}', '\App\Modules\Mpesa\Controller@subscriptionAuthorization')->name('mpesa.subscription.authorization');
        Route::post('subscription/mpesa/callback/{id}', '\App\Modules\Mpesa\Controller@subscriptionCallback')->name('mpesa.subscription.callback');

        Route::get('purchase/mpesa/authorization', '\App\Modules\Mpesa\Controller@purchaseAuthorization')->name('mpesa.purchase.authorization');
        Route::post('purchase/mpesa/callback', '\App\Modules\Mpesa\Controller@purchaseCallback')->name('mpesa.purchase.callback');
    });
});