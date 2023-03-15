<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetatagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metatags', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('priority')->nullable()->index('priority');
            $table->string('url')->default('');
            $table->string('info')->nullable();
            $table->string('page_title');
            $table->text('page_description')->nullable();
            $table->string('page_keywords')->nullable();
            $table->boolean('auto_keyword')->default(0);
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
        Schema::dropIfExists('metatags');
    }
}
