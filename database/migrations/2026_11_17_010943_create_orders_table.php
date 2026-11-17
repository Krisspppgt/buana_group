<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $t) {
            $t->increments('id');
            $t->char('code', 12)->unique();                   // contoh: BN2611160001
            $t->unsignedInteger('user_id');
            $t->unsignedTinyInteger('unit');                  // 1 air, 2 food, 3 konveksi
            // 0 menunggu bayar, 1 dibayar, 2 diproses, 3 dikirim, 4 selesai, 9 batal
            $t->unsignedTinyInteger('status')->default(0);
            $t->unsignedInteger('subtotal');
            $t->unsignedInteger('ship_fee')->default(0);
            $t->unsignedInteger('total');
            $t->string('ship_name', 40);                      // salinan alamat saat order
            $t->string('ship_phone', 15);
            $t->string('ship_addr', 150);
            $t->string('note', 100)->nullable();
            $t->dateTime('send_at')->nullable();              // jadwal kirim (Food)
            $t->timestamps();

            $t->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $t->index(['user_id', 'status']);
            $t->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};