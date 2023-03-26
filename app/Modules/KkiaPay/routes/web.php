<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 18:10
 */

Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    Route::group(['middleware' => 'auth'], function () {
        Route::get('subscription/kkiapay/{id}', '\App\Modules\KkiaPay\Controller@subscriptionAuthorization')->name('kkiapay.subscription.authorization');
        Route::get('subscription/kkiapay/callback/{id}', '\App\Modules\KkiaPay\Controller@subscriptionCallback')->name('kkiapay.subscription.callback');

        Route::get('purchase/kkiapay/authorization', '\App\Modules\KkiaPay\Controller@purchaseAuthorization')->name('kkiapay.purchase.authorization');
        Route::get('purchase/kkiapay/callback', '\App\Modules\KkiaPay\Controller@purchaseCallback')->name('kkiapay.purchase.callback');
    });
});