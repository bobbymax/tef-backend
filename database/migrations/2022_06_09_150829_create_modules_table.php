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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('label')->unique();
            $table->string('icon')->nullable();
            $table->string('path')->nullable();
            $table->string('file_path')->nullable();
            $table->bigInteger('parentId')->default(0);
            $table->boolean('isMenu')->default(false);
            $table->enum('type', ['application', 'module', 'page'])->default('application');
            $table->string('component')->nullable();
            $table->boolean('isAuthRequired')->default(false);
            $table->boolean('generatePermissions')->default(false);
            $table->boolean('isAdministration')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
};
