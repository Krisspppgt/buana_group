<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedTinyInteger('unit');                  // 1 air, 2 food, 3 konveksi
            $t->string('name', 50);
            $t->string('info', 200)->nullable();
            $t->unsignedInteger('price');                     // rupiah
            $t->unsignedInteger('price_civitas')->nullable();
            $t->unsignedInteger('price_bulk')->nullable();
            $t->unsignedSmallInteger('bulk_min')->nullable();
            $t->unsignedSmallInteger('stock')->default(0);
            $t->string('uom', 10);                            // botol, galon, box
            $t->string('photo', 60)->nullable();              // nama file saja
            $t->boolean('is_active')->default(true);

            $t->index(['unit', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
