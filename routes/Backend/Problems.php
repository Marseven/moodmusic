<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-08-08
 * Time: 21:45
 */

Route::group(['middleware' => 'role:admin_songs'], function() {
    Route::get('problems', 'ProblemsController@index')->name('problems');
    Route::get('problems/{id}', 'ProblemsController@delete')->name('problems.delete');
});