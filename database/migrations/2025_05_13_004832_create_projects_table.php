<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id')->autoIncrement()->primary();
            $table->uuid('unicode')->unique();
            $table->string('project_name', 100);
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('status');
            $table->timestamp('created')->useCurrent();
            $table->timestamp('updated')->useCurrent()->useCurrentOnUpdate();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('unicode')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('project_name_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('project_name', 100);
            $table->timestamp('created')->useCurrent();
            $table->uuid('project_id');

            $table->foreign('project_id')->references('unicode')->on('projects')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('project_is_active_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('is_active');
            $table->timestamp('created')->useCurrent();
            $table->uuid('project_id');

            $table->foreign('project_id')->references('unicode')->on('projects')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
        Schema::dropIfExists('project_name_log');
        Schema::dropIfExists('project_is_active_log');
    }
};
