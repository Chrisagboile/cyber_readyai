<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pension_guard_audit_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('actor_id')->nullable()->index();
            $table->string('action', 100)->index();
            $table->string('target_type', 100)->nullable();
            $table->unsignedBigInteger('target_id')->nullable();
            $table->json('metadata')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();

            $table->index(['target_type', 'target_id']);
        });
    }

    public function down(): void
    {
        Schema::table('pension_guard_audit_logs', function (Blueprint $table) {
            $table->dropIndex(['actor_id']);
            $table->dropIndex(['action']);
            $table->dropIndex(['target_type', 'target_id']);

            $table->dropColumn([
                'actor_id',
                'action',
                'target_type',
                'target_id',
                'metadata',
                'ip_address',
                'user_agent',
            ]);
        });
    }
};
