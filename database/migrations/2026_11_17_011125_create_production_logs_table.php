<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_logs', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedInteger('order_id');
            // 1 menunggu bahan, 2 potong, 3 jahit, 4 sablon/bordir, 5 finishing, 6 siap kirim
            $t->unsignedTinyInteger('stage');
            $t->string('note', 100)->nullable();
            $t->timestamp('created_at')->useCurrent();

            $t->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $t->index(['order_id', 'stage']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_logs');
    }
};
