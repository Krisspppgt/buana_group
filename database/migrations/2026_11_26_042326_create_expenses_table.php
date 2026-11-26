<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table): void {
            $table->id();
            $table->unsignedTinyInteger('unit')->nullable();
            $table->string('category', 40);
            $table->string('title', 120);
            $table->unsignedBigInteger('amount');
            $table->date('incurred_at');
            $table->string('note', 255)->nullable();
            $table->unsignedInteger('recorded_by')->nullable();
            $table->timestamps();

            $table->foreign('recorded_by')->references('id')->on('users')->nullOnDelete();
            $table->index(['incurred_at', 'unit']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
