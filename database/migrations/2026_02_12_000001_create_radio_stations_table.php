<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('radio_stations')) {
            return;
        }

        Schema::create('radio_stations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('image', 500)->nullable();
            $table->string('stream_url', 500);
            $table->string('frequency', 20)->nullable();
            $table->text('description')->nullable();
            $table->string('genre', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->integer('listeners_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('radio_stations');
    }
};
