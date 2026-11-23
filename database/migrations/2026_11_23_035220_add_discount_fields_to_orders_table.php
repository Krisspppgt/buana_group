<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('discount_id')->nullable()->after('ship_fee');
            $table->string('discount_code', 32)->nullable()->after('discount_id');
            $table->unsignedInteger('discount_amount')->default(0)->after('discount_code');

            $table->foreign('discount_id')
                ->references('id')
                ->on('discounts')
                ->nullOnDelete();

            $table->index(['discount_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['discount_id']);
            $table->dropIndex(['discount_id', 'user_id']);
            $table->dropColumn(['discount_id', 'discount_code', 'discount_amount']);
        });
    }
};
