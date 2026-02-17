<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Increase max upload size to 200MB to support large audio files (DJ mixes, etc.)
        DB::table('settings')
            ->where('name', 'uploads.max_size')
            ->update(['value' => 209715200]); // 200 * 1024 * 1024
    }

    public function down(): void
    {
        DB::table('settings')
            ->where('name', 'uploads.max_size')
            ->update(['value' => 20971520]); // 20MB
    }
};
