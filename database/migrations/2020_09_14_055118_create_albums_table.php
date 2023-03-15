<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->nullable()->index('user_id');
            $table->string('genre', 50)->nullable()->index('genre');
            $table->string('mood', 50)->nullable()->index('mood');
            $table->tinyInteger('type')->nullable()->index('type');
            $table->string('artistIds', 50)->index('artistId');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->string('copyright', 50)->nullable();
            $table->boolean('allow_comments')->default(1);
            $table->mediumInteger('comment_count')->default(0);
            $table->boolean('visibility')->default(1)->index('visibility');
            $table->boolean('approved')->default(0)->index('approved');
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
        Schema::dropIfExists('albums');
    }
}
