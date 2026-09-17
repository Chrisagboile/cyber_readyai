<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_attempts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('assessment_id')
                ->constrained('assessments')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->unsignedInteger('current_position')
                ->default(0);

            $table->string('status', 30)
                ->default('in_progress');

            $table->unsignedInteger('total_questions')
                ->default(0);

            $table->unsignedInteger('answered_questions')
                ->default(0);

            $table->unsignedInteger('correct_answers')
                ->default(0);

            $table->decimal('score_percentage', 5, 2)
                ->nullable();

            $table->string('risk_level', 30)
                ->nullable();

            $table->timestamp('started_at')
                ->nullable();

            $table->timestamp('expires_at')
                ->nullable();

            $table->timestamp('completed_at')
                ->nullable();

            $table->timestamps();

            $table->unique(
                ['assessment_id', 'user_id'],
                'assessment_attempts_assessment_user_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_attempts');
    }
};
