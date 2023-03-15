<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSocialsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('website_url')->after('remember_token')->nullable();
            $table->string('twitter_url')->after('remember_token')->nullable();
            $table->string('facebook_url')->after('remember_token')->nullable();
            $table->string('youtube_url')->after('remember_token')->nullable();
            $table->string('instagram_url')->after('remember_token')->nullable();
            $table->string('soundcloud_url')->after('remember_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {

        });
    }
}
