<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->index('updated_at');
            $table->index('name');
        });
        Schema::table('artists', function (Blueprint $table) {
            $table->index('updated_at');
        });
        Schema::table('albums', function (Blueprint $table) {
            $table->index('updated_at');
        });
        Schema::table('track_plays', function (Blueprint $table) {
            $table->index('created_at');
        });
    }

    public function down()
    {
        //
    }
};
