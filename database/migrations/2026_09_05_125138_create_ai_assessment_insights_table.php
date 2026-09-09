<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_assessment_insights', function (Blueprint $table) {
            $table->id();

            $table->foreignId('assessment_attempt_id')
                ->unique()
                ->constrained('assessment_attempts')
                ->cascadeOnDelete();

            $table->text('summary');

            $table->json('strengths')
                ->nullable();

            $table->json('priority_areas')
                ->nullable();

            $table->json('recommendations')
                ->nullable();

            $table->string('model')
                ->nullable();

            $table->string('prompt_version')
                ->default('1.0');

            $table->timestamp('generated_at')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_assessment_insights');
    }
};
