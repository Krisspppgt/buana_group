<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedInteger('user_id');
            $t->string('item', 30);                           // jas almamater, PDH, PDL, kaos, jaket
            $t->unsignedSmallInteger('qty');
            $t->string('sizes', 100);                         // contoh: S10,M20,L15
            $t->string('design', 60)->nullable();             // nama file desain
            $t->unsignedInteger('est_price');
            $t->unsignedInteger('final_price')->nullable();
            // 0 diajukan, 1 ditawarkan, 2 disetujui, 3 ditolak
            $t->unsignedTinyInteger('status')->default(0);
            $t->unsignedInteger('order_id')->nullable();
            $t->timestamp('created_at')->useCurrent();

            $t->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $t->foreign('order_id')->references('id')->on('orders')->nullOnDelete();
            $t->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};