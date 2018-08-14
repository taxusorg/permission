<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->string('name')->unique();
        });
        Schema::create('role_permits', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->unsignedInteger('role_id')->index();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
            $table->string('permit_key')->index();
        });
        Schema::create('role_user', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('role_id')->index();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_permits');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
    }
}
