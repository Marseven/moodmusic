<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('song_id')->nullable()->index('song_id');
            $table->string('youtube_id', 50)->nullable();
            $table->string('stream_url')->nullable();
            $table->smallInteger('user_id')->nullable()->index('user_id');
            $table->boolean('explicit')->default(0)->index('explicit');
            $table->boolean('selling')->default(0)->index('selling');
            $table->decimal('price', 10)->nullable()->default(0.00);
            $table->string('genre', 50)->nullable()->index('genre');
            $table->string('mood', 50)->nullable()->index('mood');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('access')->nullable();
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
