<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-01
 * Time: 20:34
 */
Route::get('channel/{slug}', 'ChannelController@index')->name('channel');
Route::get('genre-channel/{alt_name}/{slug}', 'ChannelController@index')->name('channel.genre');
Route::get('mood-channel/{alt_name}/{slug}', 'ChannelController@index')->name('channel.mood');