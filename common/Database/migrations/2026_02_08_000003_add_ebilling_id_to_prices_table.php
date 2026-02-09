<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('prices', 'ebilling_id')) {
            Schema::table('prices', function (Blueprint $table) {
                $table->string('ebilling_id', 50)->nullable()->after('paypal_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('prices', 'ebilling_id')) {
            Schema::table('prices', function (Blueprint $table) {
                $table->dropColumn('ebilling_id');
            });
        }
    }
};
