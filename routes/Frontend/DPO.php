<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 18:10
 */


Route::group(['middleware' => 'auth'], function () {
    Route::get('subscription/dpo/{id}', '\App\Modules\DPO\Controller@subscriptionAuthorization')->name('dpo.subscription.authorization');
    Route::get('subscription/dpo/callback/{id}', '\App\Modules\DPO\Controller@subscriptionCallback')->name('dpo.subscription.callback');

    Route::get('purchase/dpo/authorization', '\App\Modules\DPO\Controller@purchaseAuthorization')->name('dpo.purchase.authorization');
    Route::get('purchase/dpo/callback', '\App\Modules\DPO\Controller@purchaseCallback')->name('dpo.purchase.callback');
});