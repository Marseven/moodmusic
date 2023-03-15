<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->boolean('mp3')->default(0)->index('mp3');
            $table->boolean('hd')->nullable()->default(0)->index('hd');
            $table->boolean('hls')->default(0)->index('hls');
            $table->smallInteger('user_id')->nullable()->index('user_id');
            $table->string('genre', 50)->nullable()->index('genre');
            $table->string('mood', 50)->nullable()->index('mood');
            $table->string('title');
            $table->text('description')->nullable();
            $table->smallInteger('duration')->nullable();
            $table->string('artistIds', 50)->nullable();
            $table->integer('loves')->default(0);
            $table->integer('collectors')->default(0)->index('collectors');
            $table->integer('plays')->default(0);
            $table->date('released_at')->nullable();
            $table->string('copyright')->nullable();
            $table->boolean('allow_download')->default(1)->index('allow_download');
            $table->mediumInteger('download_count')->default(0);
            $table->boolean('allow_comments')->default(1);
            $table->mediumInteger('comment_count')->default(0);
            $table->boolean('visibility')->default(1)->index('visibility');
            $table->boolean('approved')->default(0)->index('approve');
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
        Schema::dropIfExists('songs');
    }
}
