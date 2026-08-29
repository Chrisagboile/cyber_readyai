<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('question_id')
                ->constrained('questions')
                ->cascadeOnDelete();

            $table->integer('question_order');

            $table->foreignId('assessment_attempt_id')
                ->nullable()
                ->constrained('assessment_attempts')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique([
                'employee_id',
                'question_id',
                'assessment_attempt_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_questions');
    }
};
