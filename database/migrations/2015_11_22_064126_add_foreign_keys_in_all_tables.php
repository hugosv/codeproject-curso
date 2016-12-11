<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysInAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_files', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });

        Schema::table('project_tasks', function(Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });

        Schema::table('project_members', function(Blueprint $table) {
             $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
             $table->foreign('member_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('project_notes', function(Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });

        Schema::table('projects', function(Blueprint$table) {
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_files', function (Blueprint $table) {
            $table->dropForeign('project_files_project_id_foreign');
        });

        Schema::table('project_tasks', function (Blueprint $table) {
            $table->dropForeign('project_tasks_project_id_foreign');
        });

        Schema::table('project_members', function (Blueprint $table) {
            $table->dropForeign('project_members_project_id_foreign');
            $table->dropForeign('project_members_member_id_foreign');
        });

        Schema::table('project_notes', function (Blueprint $table) {
            $table->dropForeign('project_notes_project_id_foreign');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign('projects_owner_id_foreign');
            $table->dropForeign('projects_client_id_foreign');
        });
    }
}
