<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vibes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('display_name', 255);
            $table->string('emoji', 10)->nullable();
            $table->string('color', 7)->default('#7351EA');
            $table->unsignedInteger('genre_id')->nullable();
            $table->unsignedInteger('channel_id')->nullable();
            $table->integer('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('genre_id')->references('id')->on('genres')->nullOnDelete();
            $table->foreign('channel_id')->references('id')->on('channels')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vibes');
    }
};
