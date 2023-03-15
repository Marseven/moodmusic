<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-07-21
 * Time: 23:03
 */

Route::group(['middleware' => 'role:admin_media_manager'], function() {
    Route::get('media', 'MediaController@index')->name('media-index');
    Route::get('media/download', 'MediaController@download')->name('media-download');
    Route::delete('media/delete', 'MediaController@delete')->name('media-delete');
    Route::put('media/move', 'MediaController@move')->name('media-move');
    Route::post('media/upload', 'MediaController@upload')->name('media-upload');
    Route::post('media/folder', 'MediaController@newFolder')->name('media-new-folder');
});