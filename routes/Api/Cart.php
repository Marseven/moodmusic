<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:35
 */

Route::get('cart', 'CartController@index')->name('cart');
Route::post('cart/overview', 'CartController@overview')->name('cart.overview');
Route::post('cart/add', 'CartController@add')->name('cart.add');
Route::post('cart/remove', 'CartController@remove')->name('cart.remove');
Route::post('cart/coupon/apply', 'CartController@applyCoupon')->name('cart.coupon.apply');


