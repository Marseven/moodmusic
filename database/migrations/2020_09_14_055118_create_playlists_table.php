<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaylistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->boolean('collaboration')->default(0)->index('collaborate');
            $table->string('genre', 50)->nullable();
            $table->string('mood', 50)->nullable()->index('mood');
            $table->string('title');
            $table->string('description')->nullable();
            $table->mediumInteger('loves')->default(0);
            $table->boolean('allow_comments')->default(1);
            $table->smallInteger('comment_count')->default(0);
            $table->boolean('visibility')->default(1);
            $table->timestamps();
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
        Schema::dropIfExists('playlists');
    }
}
