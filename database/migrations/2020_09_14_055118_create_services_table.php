<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->decimal('price', 10)->nullable()->default(0.00);
            $table->string('description');
            $table->smallInteger('role_id');
            $table->boolean('active')->default(0);
            $table->boolean('trial')->default(0);
            $table->smallInteger('trial_period')->nullable();
            $table->enum('trial_period_format', ['D', 'W', 'M', 'Y'])->nullable();
            $table->smallInteger('plan_period')->default(0);
            $table->enum('plan_period_format', ['D', 'W', 'M', 'Y']);
            $table->timestamps();
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
        Schema::dropIfExists('services');
    }
}
