<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->unsignedInteger('loyalty_points')->default(0)->after('campus_id');
        });

        Schema::table('orders', function (Blueprint $t) {
            $t->unsignedInteger('points_redeemed')->default(0)->after('ship_fee');
            $t->unsignedInteger('points_earned')->default(0)->after('total');
        });

        Schema::create('point_histories', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedInteger('user_id');
            $t->unsignedTinyInteger('unit')->nullable();
            $t->string('type', 15); // earn, redeem
            $t->integer('points');
            $t->string('reference_code', 25)->nullable();
            $t->string('description', 120);
            $t->timestamps();

            $t->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $t->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_histories');

        Schema::table('orders', function (Blueprint $t) {
            $t->dropColumn(['points_redeemed', 'points_earned']);
        });

        Schema::table('users', function (Blueprint $t) {
            $t->dropColumn('loyalty_points');
        });
    }
};
