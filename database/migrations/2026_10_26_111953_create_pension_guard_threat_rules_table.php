<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pension_guard_threat_rules', function (Blueprint $table) {
            $table->id();
            $table->string('rule_key', 60)->unique();
            $table->string('title');
            $table->text('description');
            $table->string('severity', 20)->default('medium');
            $table->text('recommended_action');
            $table->string('official_source_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'severity']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pension_guard_threat_rules');
    }
};
