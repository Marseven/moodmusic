<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ad_spots')) {
            return;
        }

        Schema::create('ad_spots', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('audio_url', 500);
            $table->string('image_url', 500)->nullable();
            $table->string('click_url', 500)->nullable();
            $table->unsignedInteger('duration');
            $table->boolean('active')->default(true);
            $table->integer('priority')->default(0);
            $table->integer('impressions')->default(0);
            $table->integer('clicks')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_spots');
    }
};
