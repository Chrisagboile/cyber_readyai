<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pension_guard_official_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('url', 2048);
            $table->string('source_type', 60)->default('guidance');
            $table->text('purpose');
            $table->date('last_reviewed_at')->nullable();
            $table->date('next_review_due')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'next_review_due']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pension_guard_official_sources');
    }
};
