<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('purchases')) {
            return;
        }

        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('purchasable_type', 50);
            $table->unsignedBigInteger('purchasable_id');
            $table->string('gateway_name', 20);
            $table->string('gateway_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('XAF');
            $table->decimal('commission_rate', 5, 2)->default(0);
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->decimal('artist_amount', 10, 2)->default(0);
            $table->string('status', 20)->default('pending');
            $table->string('reference')->unique();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['purchasable_type', 'purchasable_id']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
