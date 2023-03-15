<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-07-21
 * Time: 13:17
 */


Route::group(['middleware' => 'role:admin_metatags'], function() {
    Route::get('metatags', 'MetaTagController@index')->name('metatags');
    Route::post('metatags', 'MetaTagController@addPost')->name('metatags.add.post');
    Route::post('metatags/sort', 'MetaTagController@sort')->name('metatags.sort.post');
    Route::get('metatags/edit/{id}', 'MetaTagController@edit')->name('metatags.edit');
    Route::post('metatags/edit/{id}', 'MetaTagController@editPost')->name('metatags.edit.post');
    Route::get('metatags/delete/{id}', 'MetaTagController@delete')->name('metatags.delete');

});