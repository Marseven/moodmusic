<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tracks', function (Blueprint $table) {
            if (!Schema::hasColumn('tracks', 'price')) {
                $table->decimal('price', 10, 2)->nullable()->after('duration');
            }
            if (!Schema::hasColumn('tracks', 'currency')) {
                $table->string('currency', 3)->default('XAF')->after('price');
            }
        });

        Schema::table('albums', function (Blueprint $table) {
            if (!Schema::hasColumn('albums', 'price')) {
                $table->decimal('price', 10, 2)->nullable()->after('name');
            }
            if (!Schema::hasColumn('albums', 'currency')) {
                $table->string('currency', 3)->default('XAF')->after('price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->dropColumn(['price', 'currency']);
        });

        Schema::table('albums', function (Blueprint $table) {
            $table->dropColumn(['price', 'currency']);
        });
    }
};
