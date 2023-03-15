<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->index('user_id');
            $table->integer('historyable_id');
            $table->string('historyable_type', 50);
            $table->string('ownerable_type', 50)->nullable();
            $table->integer('ownerable_id')->nullable();
            $table->mediumInteger('interaction_count')->default(0);
            $table->timestamps();
            $table->unique(['user_id', 'historyable_id', 'historyable_type'], 'UNIQUE_FOR_USER_LOVE');
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
        Schema::dropIfExists('histories');
    }
}
