<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('orderable_id');
            $table->string('orderable_type', 50)->nullable();
            $table->string('payment', 20)->nullable();
            $table->decimal('amount', 10)->unsigned()->nullable();
            $table->decimal('commission', 10)->default(0.00);
            $table->string('currency', 50)->index('currency');
            $table->boolean('payment_status')->default(0)->index('payment_status');
            $table->string('transaction_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('orders');
    }
}
