<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleHasPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->foreignId('role_id');
            $table->foreignId('permission_id');

            $table->foreign('role_id')->on('roles')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('permission_id')->on('permissions')->references('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_has_permissions');
    }
}
