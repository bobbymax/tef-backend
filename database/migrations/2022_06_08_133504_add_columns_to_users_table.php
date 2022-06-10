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
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile')->unique()->nullable()->after('email');
            $table->date('date_joined')->nullable()->after('email_verified_at');
            $table->string('designation')->nullable()->after('name');
            $table->enum('type', ['subscriber', 'staff', 'dispatch', 'adhoc', 'support', 'influencer', 'ambassador'])->default('subscriber')->after('remember_token');
            $table->decimal('score', $precision=30, $scale=2)->default(0)->after('date_joined');
            $table->boolean('isAdministrator')->default(false)->after('type');
            $table->boolean('blacklisted')->default(false)->after('isAdministrator');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('mobile');
            $table->dropColumn('date_joined');
            $table->dropColumn('designation');
            $table->dropColumn('type');
            $table->dropColumn('score');
            $table->dropColumn('isAdministrator');
            $table->dropColumn('blacklisted');
        });
    }
};
