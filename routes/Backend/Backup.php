<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-07-22
 * Time: 02:35
 */

Route::group(['middleware' => 'role:admin_backup'], function() {
    Route::get('backup', 'BackupController@index')->name('backup-list');
    Route::get('backup/download', 'BackupController@download')->name('backup-download');
    Route::post('backup/run', 'BackupController@run')->name('backup-run');
    Route::post('backup/run/db', 'BackupController@runDB')->name('backup-run-db');
    Route::delete('backup/delete', 'BackupController@delete')->name('backup-delete');
});