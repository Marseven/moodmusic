<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create original content categories table
        if (!Schema::hasTable('original_content_categories')) {
            Schema::create('original_content_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100)->unique();
                $table->string('display_name', 100);
                $table->string('description', 500)->nullable();
                $table->string('icon', 100)->nullable();
                $table->integer('position')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            // Seed default categories
            DB::table('original_content_categories')->insert([
                [
                    'name' => 'mix',
                    'display_name' => 'MixDJ',
                    'description' => 'DJ Mixes & Sets',
                    'icon' => 'disc-3',
                    'position' => 0,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'beat',
                    'display_name' => 'Beatmaker',
                    'description' => 'Instrumentals & Beats',
                    'icon' => 'audio-lines',
                    'position' => 1,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // Add columns to tracks table
        Schema::table('tracks', function (Blueprint $table) {
            if (!Schema::hasColumn('tracks', 'is_original_content')) {
                $table->boolean('is_original_content')->default(false)->after('currency');
            }
            if (!Schema::hasColumn('tracks', 'original_content_category_id')) {
                $table->unsignedBigInteger('original_content_category_id')->nullable()->after('is_original_content');
                $table->foreign('original_content_category_id')
                    ->references('id')
                    ->on('original_content_categories')
                    ->nullOnDelete();
            }
            if (!Schema::hasColumn('tracks', 'is_live')) {
                $table->boolean('is_live')->default(false)->after('original_content_category_id');
            }
            if (!Schema::hasColumn('tracks', 'preview_path')) {
                $table->string('preview_path', 500)->nullable()->after('is_live');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->dropForeign(['original_content_category_id']);
            $table->dropColumn([
                'is_original_content',
                'original_content_category_id',
                'is_live',
                'preview_path',
            ]);
        });

        Schema::dropIfExists('original_content_categories');
    }
};
