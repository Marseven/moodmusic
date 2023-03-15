<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episodes', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->increments('id');
            $table->mediumInteger('season')->nullable()->index('season');
            $table->mediumInteger('number')->nullable()->index('number');
            $table->boolean('type')->default(1);
            $table->integer('user_id')->nullable()->index('user_id');
            $table->boolean('hls')->nullable();
            $table->boolean('mp3')->nullable();
            $table->integer('podcast_id')->index('podcast_id');
            $table->string('title')->default('');
            $table->text('description')->nullable();
            $table->string('access')->nullable();
            $table->boolean('explicit')->default(0);
            $table->string('stream_url')->nullable();
            $table->boolean('allow_comments')->default(1)->index('allow_comments');
            $table->integer('comment_count')->default(0);
            $table->boolean('allow_download')->default(0);
            $table->integer('download_count')->default(0);
            $table->integer('loves')->default(0);
            $table->integer('play_count')->default(0)->index('play_count');
            $table->integer('failed_count')->default(0)->index('failed_count');
            $table->mediumInteger('duration')->default(0);
            $table->boolean('visibility')->default(1);
            $table->boolean('approved')->default(1)->index('approved');
            $table->boolean('pending')->default(0)->index('pending');
            $table->timestamps();
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
        DB::statement('ALTER TABLE episodes ADD FULLTEXT searchcontent (title, description)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('episodes');
    }
}
