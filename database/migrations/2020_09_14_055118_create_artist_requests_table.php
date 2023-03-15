<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtistRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artist_requests', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->nullable()->unique('user_id');
            $table->integer('artist_id')->nullable();
            $table->string('artist_name');
            $table->string('phone', 50)->nullable();
            $table->string('ext', 10)->nullable();
            $table->string('affiliation')->nullable();
            $table->text('message')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
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
        Schema::dropIfExists('artist_requests');
    }
}
