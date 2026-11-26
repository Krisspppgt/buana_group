<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table): void {
            $table->timestamp('due_date')->nullable()->after('paid_at');
            $table->index(['method', 'status', 'due_date']);
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table): void {
            $table->dropIndex(['method', 'status', 'due_date']);
            $table->dropColumn('due_date');
        });
    }
};
