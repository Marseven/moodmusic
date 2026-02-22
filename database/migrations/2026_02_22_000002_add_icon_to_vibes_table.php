<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vibes', function (Blueprint $table) {
            $table->string('icon', 50)->nullable()->after('emoji');
        });
    }

    public function down(): void
    {
        Schema::table('vibes', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }
};
