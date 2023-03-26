<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-02
 * Time: 20:15
 */
use Illuminate\Routing\Route;
Route::post('visitor/feedback', 'FeedbackController@index')->name('feedback');
