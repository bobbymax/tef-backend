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
        Schema::create('internal_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cart_id')->unsigned();
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('trnxId')->unique();
            $table->string('table_no')->nullable();
            $table->string('recipient')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->decimal('amount_received', $precision=30, $scale=2)->default(0);
            $table->longText('additional_info')->nullable();
            $table->enum('payment_method', ['electronic', 'cash'])->default('electronic');
            $table->enum('status', ['pending', 'processing', 'fulfilled', 'cancelled'])->default('pending');
            $table->boolean('paid')->default(false);
            $table->boolean('closed')->default(false);
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
        Schema::dropIfExists('internal_orders');
    }
};
