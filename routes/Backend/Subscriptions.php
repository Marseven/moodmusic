<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-06
 * Time: 23:13
 */
use Illuminate\Support\Facades\Route;
Route::group(['middleware' => 'role:admin_subscriptions'], function() {
    Route::get('subscriptions', 'SubscriptionsController@index')->name('subscriptions');
    Route::get('subscriptions/edit/{id}', 'SubscriptionsController@edit')->name('subscriptions.edit');
    Route::post('subscriptions/edit/{id}', 'SubscriptionsController@editPost')->name('subscriptions.edit.post');
    Route::get('subscriptions/delete/{id}', 'SubscriptionsController@delete')->name('subscriptions.delete');
});
