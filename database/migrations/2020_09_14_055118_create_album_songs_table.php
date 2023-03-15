<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('album_songs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('song_id');
            $table->integer('album_id');
            $table->smallInteger('priority')->default(0);
            $table->unique(['song_id', 'album_id'], 'AlbumSong');
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('album_songs');
    }
}
