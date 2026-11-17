<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedInteger('user_id');
            $t->string('label', 15);              // Rumah, Kantor, Kampus
            $t->string('receiver', 40);
            $t->string('phone', 15);
            $t->string('detail', 150);
            $t->decimal('lat', 9, 6)->nullable();
            $t->decimal('lng', 9, 6)->nullable();
            $t->boolean('is_main')->default(false);

            $t->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
