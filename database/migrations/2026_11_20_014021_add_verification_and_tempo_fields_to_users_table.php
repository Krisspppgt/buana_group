<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->unsignedTinyInteger('requested_role')->nullable()->after('role');
            $table->unsignedTinyInteger('verification_status')->default(0)->after('requested_role');
            $table->string('faculty', 80)->nullable()->after('campus_id');
            $table->string('company_name', 100)->nullable()->after('faculty');
            $table->string('position', 60)->nullable()->after('company_name');
            $table->string('verification_note', 255)->nullable()->after('position');
            $table->timestamp('verified_at')->nullable()->after('verification_note');
            $table->boolean('tempo_approved')->default(false)->after('verified_at');
            $table->unsignedBigInteger('tempo_limit')->default(0)->after('tempo_approved');

            $table->index('verification_status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropIndex(['verification_status']);
            $table->dropColumn([
                'requested_role',
                'verification_status',
                'faculty',
                'company_name',
                'position',
                'verification_note',
                'verified_at',
                'tempo_approved',
                'tempo_limit',
            ]);
        });
    }
};
