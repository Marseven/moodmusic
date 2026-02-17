<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ad_spots', function (Blueprint $table) {
            $table->enum('type', ['audio', 'banner'])->default('audio')->after('name');
            $table->string('audio_url', 500)->nullable()->change();
            $table->integer('duration')->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('ad_spots', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->string('audio_url', 500)->nullable(false)->change();
            $table->integer('duration')->nullable(false)->default(15)->change();
        });
    }
};
