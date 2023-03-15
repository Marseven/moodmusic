<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-23
 * Time: 09:58
 */

/*
 * Edit Static Module
*/

Route::group(['middleware' => 'role:admin_earnings'], function() {
    Route::get('coupons', 'CouponsController@index')->name('coupons');
    Route::get('coupons/add', 'CouponsController@add')->name('coupons.add');
    Route::post('coupons/add ', 'CouponsController@addPost')->name('coupons.add.post');
    Route::get('coupons/edit/{id}', 'CouponsController@edit')->name('coupons.edit');
    Route::post('coupons/edit/{id}', 'CouponsController@editPost')->name('coupons.edit.post');
    Route::get('coupons/delete/{id}', 'CouponsController@delete')->name('coupons.delete');
});