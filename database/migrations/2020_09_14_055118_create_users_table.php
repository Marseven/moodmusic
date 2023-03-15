<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name');
            $table->string('username')->nullable()->unique('username');
            $table->string('password');
            $table->string('session_id')->nullable();
            $table->string('email')->nullable()->unique('email');
            $table->boolean('email_verified')->default(0)->index('email_verified');
            $table->string('email_verified_code')->nullable()->index('email_verified_code');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('remember_token')->nullable();
            $table->boolean('banned')->default(0)->index('banned');
            $table->integer('artist_id')->default(0)->index('artistId');
            $table->integer('playlist_count')->default(0);
            $table->integer('collection_count')->default(0);
            $table->integer('favorite_count')->default(0);
            $table->integer('following_count')->default(0);
            $table->mediumInteger('follower_count')->default(0);
            $table->string('logged_ip', 46)->nullable();
            $table->timestamp('last_activity')->nullable()->index('last_activity');
            $table->timestamp('last_seen_notif')->nullable()->index('last_seen_notif');
            $table->timestamp('notification')->nullable();
            $table->string('country')->nullable();
            $table->mediumText('bio')->nullable();
            $table->string('gender', 50)->nullable();
            $table->timestamp('birth')->nullable();
            $table->boolean('allow_comments')->default(1);
            $table->smallInteger('comment_count')->default(0);
            $table->boolean('restore_queue')->default(0);
            $table->boolean('persist_shuffle')->default(0);
            $table->boolean('play_pause_fade')->default(0);
            $table->boolean('disablePlayerShortcuts')->default(0);
            $table->boolean('crossfade_amount')->default(5);
            $table->boolean('hd_streaming')->default(1);
            $table->boolean('activity_privacy')->default(0);
            $table->boolean('notif_follower')->default(0);
            $table->boolean('notif_playlist')->default(0);
            $table->boolean('notif_shares')->default(0);
            $table->boolean('notif_features')->default(0);
            $table->boolean('trialed')->nullable()->default(0)->index('users_stripe_id_index');
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
        Schema::dropIfExists('users');
    }
}
