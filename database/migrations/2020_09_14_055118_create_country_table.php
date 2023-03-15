<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country', function (Blueprint $table) {
            $table->integer('id', true);
            $table->char('code', 3)->default('')->index('code');
            $table->char('name', 52)->default('');
            $table->string('continent', 50)->nullable()->index('continent');
            $table->mediumInteger('region_id')->nullable()->index('region_id');
            $table->char('local_name', 45)->default('');
            $table->char('government_form', 45)->default('')->index('government_form');
            $table->char('code2', 2)->default('');
            $table->boolean('fixed')->default(0)->index('fixed');
            $table->boolean('visibility')->default(1)->index('visibility');
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
        Schema::dropIfExists('country');
    }
}
