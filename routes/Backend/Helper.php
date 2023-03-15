<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-07-22
 * Time: 18:37
 */

Route::group(['middleware' => 'role:admin_terminal'], function() {
    Route::get('helpers/terminal/artisan', 'TerminalController@artisan')->name('help.terminal.artisan');
    Route::post('helpers/terminal/artisan', 'TerminalController@runArtisan')->name('help.terminal.artisan.post');
});