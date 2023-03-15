<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable()->index('user_id');
            $table->integer('object_id')->nullable()->index('object_id');
            $table->integer('notificationable_id');
            $table->string('notificationable_type', 50)->nullable();
            $table->integer('hostable_id');
            $table->string('hostable_type', 50)->nullable();
            $table->string('action', 30)->index('action');
            $table->timestamps();
            $table->index(['notificationable_id', 'notificationable_type'], 'INDEX_FOR_DELETE');
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
        Schema::dropIfExists('notifications');
    }
}
