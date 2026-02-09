<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('subscriptions', 'reference')) {
                $table->string('reference')->nullable()->after('gateway_id');
            }
            if (!Schema::hasColumn('subscriptions', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('renews_at');
            }
            if (!Schema::hasColumn('subscriptions', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('reference');
            }
            if (!Schema::hasColumn('subscriptions', 'operator')) {
                $table->string('operator')->nullable()->after('transaction_id');
            }
            if (!Schema::hasColumn('subscriptions', 'amount')) {
                $table->decimal('amount', 13, 2)->nullable()->after('operator');
            }
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $columns = ['reference', 'paid_at', 'transaction_id', 'operator', 'amount'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('subscriptions', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
