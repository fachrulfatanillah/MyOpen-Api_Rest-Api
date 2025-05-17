<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement()->primary();
            $table->uuid('unicode')->unique();
            $table->string('google_id', 100)->unique()->nullable();
            $table->string('name', 100);
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 100)->unique();
            $table->boolean('email_verified')->default(false);
            $table->string('image_url')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('level')->default(0);
            $table->timestamp('created')->useCurrent();
            $table->timestamp('updated')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('users_level_log', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement()->primary();
            $table->tinyInteger('level');
            $table->timestamp('created')->nullable();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('unicode')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('users_status_log', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement()->primary();
            $table->tinyInteger('status');
            $table->timestamp('created')->nullable();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('unicode')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('users_level_log');
        Schema::dropIfExists('users_status_log');
    }
};
