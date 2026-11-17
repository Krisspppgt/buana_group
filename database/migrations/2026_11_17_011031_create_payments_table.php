<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedInteger('order_id');
            $t->string('ref', 40)->unique();                  // ID transaksi gateway
            $t->string('method', 15)->nullable();             // qris, va_bca, gopay, ...
            $t->unsignedInteger('amount');
            $t->unsignedTinyInteger('status')->default(0);    // 0 pending, 1 sukses, 2 gagal/expired
            $t->timestamp('paid_at')->nullable();
            $t->timestamps();

            $t->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};