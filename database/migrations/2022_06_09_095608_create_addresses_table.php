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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('house_no')->nullable();
            $table->text('street_one')->nullable();
            $table->text('street_two')->nullable();
            $table->string('area')->nullable();
            $table->string('city')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('state')->nullable();
            $table->boolean('isCurrent')->default(false);
            $table->bigInteger('addressable_id')->unsigned();
            $table->string('addressable_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
};
