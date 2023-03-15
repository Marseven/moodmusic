<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artists', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('genre', 50)->nullable()->index('genre');
            $table->string('mood', 50)->nullable()->index('mood');
            $table->text('bio')->nullable();
            $table->string('name');
            $table->string('location')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->smallInteger('loves')->default(0);
            $table->boolean('allow_comments')->default(1);
            $table->smallInteger('comment_count')->nullable()->default(0);
            $table->boolean('visibility')->default(1)->index('visibility');
            $table->boolean('verified')->default(0)->index('verified');
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
        Schema::dropIfExists('artists');
    }
}
