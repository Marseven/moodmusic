<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreamStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stream_stats', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->integer('streamable_id');
            $table->string('streamable_type', 50);
            $table->decimal('revenue', 10, 6)->default(0.000000);
            $table->string('ip', 46)->nullable();
            $table->timestamps();
            $table->index(['user_id', 'streamable_type'], 'USER_STREAM_TYPE');
            $table->unique(['streamable_id', 'streamable_type', 'ip'], 'UNIQUE_STREAM');
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
        Schema::dropIfExists('stream_stats');
    }
}
