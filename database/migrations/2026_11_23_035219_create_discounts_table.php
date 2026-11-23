<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 80);
            $table->string('code', 32)->unique();
            $table->string('description', 160)->nullable();
            $table->string('type', 16)->default('percentage'); // percentage | fixed
            $table->unsignedInteger('value');
            $table->unsignedInteger('max_discount')->nullable();
            $table->unsignedInteger('min_subtotal')->default(0);
            $table->unsignedTinyInteger('unit')->nullable(); // null = berlaku semua unit
            $table->unsignedInteger('quota')->nullable();    // null = tanpa batas kuota
            $table->unsignedInteger('used_count')->default(0);
            $table->unsignedInteger('max_uses_per_user')->nullable();
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'starts_at', 'expires_at']);
            $table->index(['unit', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
