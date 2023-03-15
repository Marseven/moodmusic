<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('gate', 50)->nullable()->index('payment');
            $table->boolean('auto_billing')->default(1);
            $table->smallInteger('cycles')->default(0);
            $table->integer('user_id')->unique('user_id');
            $table->string('token')->nullable();
            $table->integer('service_id');
            $table->string('transaction_id')->nullable();
            $table->timestamp('last_payment_date')->nullable();
            $table->timestamp('next_billing_date')->nullable();
            $table->double('amount', 8, 2)->unsigned()->default(0.00);
            $table->string('currency', 50)->nullable();
            $table->boolean('payment_status')->unsigned()->default(0);
            $table->timestamp('trial_end')->nullable();
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
        Schema::dropIfExists('subscriptions');
    }
}
