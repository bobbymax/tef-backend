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
        Schema::table('internal_orders', function (Blueprint $table) {
            $table->decimal('total_amount', $precision=30, $scale=2)->default(0)->after('trnxId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('internal_orders', function (Blueprint $table) {
            $table->dropColumn('total_amount');
        });
    }
};
