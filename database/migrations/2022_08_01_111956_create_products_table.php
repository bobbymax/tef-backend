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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('brand_id')->unsigned();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->bigInteger('classification_id')->unsigned();
            $table->foreign('classification_id')->references('id')->on('classifications')->onDelete('cascade');
            $table->string('title');
            $table->string('label')->unique();
            $table->longText('description')->nullable();
            $table->decimal('price', $precision=30, $scale=2)->default(0);
            $table->decimal('vip', $precision=30, $scale=2)->default(0);
            $table->bigInteger('discount')->default(0);
            $table->boolean('inStock')->default(true);
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
        Schema::dropIfExists('products');
    }
};
