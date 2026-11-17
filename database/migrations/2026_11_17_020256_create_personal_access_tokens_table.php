<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_access_tokens', function (Blueprint $t) {
            $t->increments('id');
            $t->string('tokenable_type', 20);
            $t->unsignedInteger('tokenable_id');
            $t->string('name', 30);
            $t->char('token', 64)->unique();
            $t->string('abilities', 100)->nullable();
            $t->timestamp('last_used_at')->nullable();
            $t->timestamp('expires_at')->nullable()->index();
            $t->timestamps();

            $t->index(['tokenable_type', 'tokenable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
