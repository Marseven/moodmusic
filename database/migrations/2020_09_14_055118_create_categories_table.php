<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->mediumInteger('id', true);
            $table->mediumInteger('parent_id')->default(0);
            $table->mediumInteger('posi')->default(1);
            $table->string('name');
            $table->string('alt_name');
            $table->string('description')->nullable();
            $table->string('news_sort', 10)->nullable();
            $table->string('news_msort', 4)->nullable();
            $table->smallInteger('news_number')->default(0);
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->boolean('show_sub')->default(0);
            $table->boolean('allow_rss')->default(1);
            $table->boolean('disable_search')->default(0);
            $table->boolean('disable_main')->default(0);
            $table->boolean('disable_comments')->default(0);
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
        Schema::dropIfExists('categories');
    }
}
