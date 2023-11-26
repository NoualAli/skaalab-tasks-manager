<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content')->nullable();
            $table->tinyInteger('priority')->default(1);
            $table->timestamp('finished_at')->nullable();
            $table->timestamp('deadline')->nullable();
            $table->unsignedInteger('assigned_to_id')->nullable();
            $table->unsignedInteger('created_by_id');
            $table->unsignedInteger('validated_by_id')->nullable();
            $table->timestamps();

            $table->foreign('assigned_to_id')->on('users')->references('id')->onDelete('SET NULL')->onUpdate('cascade');
            $table->foreign('created_by_id')->on('users')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('validated_by_id')->on('users')->references('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
