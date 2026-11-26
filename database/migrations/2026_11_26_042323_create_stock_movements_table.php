<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('order_id')->nullable();
            $table->string('type', 20);
            $table->string('reason', 40);
            $table->unsignedInteger('qty');
            $table->integer('stock_before');
            $table->integer('stock_after');
            $table->string('note', 200)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('order_id')->references('id')->on('orders')->nullOnDelete();
            $table->index(['product_id', 'created_at']);
            $table->index(['type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
