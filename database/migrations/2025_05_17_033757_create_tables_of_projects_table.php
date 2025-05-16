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
        Schema::create('tables_of_projects', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement()->primary();
            $table->uuid('unicode')->unique();
            $table->string('table_name', 100);
            $table->tinyInteger('status');
            $table->timestamp('created')->useCurrent();
            $table->timestamp('updated')->useCurrent()->useCurrentOnUpdate();
            $table->uuid('project_id');

            $table->foreign('project_id')->references('unicode')->on('projects')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('tables_of_projects_name_log', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement()->primary();
            $table->string('table_name', 100);
            $table->timestamp('created')->useCurrent();
            $table->uuid('table_id');

            $table->foreign('table_id')->references('unicode')->on('tables_of_projects')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('tables_of_projects_status_log', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement()->primary();
            $table->tinyInteger('status');
            $table->timestamp('created')->useCurrent();
            $table->uuid('table_id');

            $table->foreign('table_id')->references('unicode')->on('tables_of_projects')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables_of_projects');
        Schema::dropIfExists('tables_of_projects_name_log');
        Schema::dropIfExists('tables_of_projects_status_log');
    }
};
