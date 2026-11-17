<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedInteger('order_id');
            $t->unsignedInteger('product_id');
            $t->unsignedSmallInteger('qty');
            $t->unsignedInteger('price');                     // harga satuan saat order

            $t->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $t->foreign('product_id')->references('id')->on('products')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};