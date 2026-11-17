<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Menggantikan migration bawaan Laravel (users, password_reset_tokens).
// Tabel sessions dihapus: pakai SESSION_DRIVER=file di .env.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $t) {
            $t->increments('id');
            $t->string('name', 40);
            $t->string('email', 50)->nullable()->unique();
            $t->string('phone', 15)->unique();
            $t->string('password', 60);                 // hash bcrypt = 60 karakter
            $t->unsignedTinyInteger('role')->default(1); // 1 pelanggan, 2 admin, 3 kurir
            $t->string('campus_id', 20)->nullable();     // NIM / NIP
            $t->string('fcm_token', 180)->nullable();
            $t->string('remember_token', 60)->nullable();
            $t->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $t) {
            $t->string('email', 50)->primary();
            $t->string('token', 60);
            $t->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
