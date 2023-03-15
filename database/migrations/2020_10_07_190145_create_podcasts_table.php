<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePodcastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('podcasts', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->increments('id');
            $table->boolean('user_id')->nullable()->index('user_id');
            $table->integer('artist_id')->nullable()->index('artist_id');
            $table->string('category', 50)->nullable()->index('category');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('country_code', 3)->nullable()->index('country_code');
            $table->mediumInteger('language_id')->nullable()->index('language_id');
            $table->string('rss_feed_url')->nullable();
            $table->boolean('allow_comments')->default(1)->index('allow_comments');
            $table->mediumInteger('comment_count')->default(0);
            $table->boolean('allow_download')->default(0)->index('allow_download');
            $table->integer('loves')->default(0);
            $table->boolean('explicit')->default(0)->index('explicit');
            $table->boolean('visibility')->default(1);
            $table->boolean('approved')->nullable()->default(1)->index('approved');
            $table->timestamps();
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
        DB::statement('ALTER TABLE podcasts ADD FULLTEXT searchcontent (title, description)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('podcasts');
    }
}
