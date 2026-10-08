<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_test_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('public_test_attempt_id')
                ->constrained('public_test_attempts')
                ->cascadeOnDelete();

            /*
             * Shared question bank.
             */
            $table->foreignId('question_id')
                ->constrained('questions')
                ->restrictOnDelete();

            /*
             * Shared question options.
             */
            $table->foreignId('question_option_id')
                ->constrained('question_options')
                ->restrictOnDelete();

            $table->boolean('is_correct')
                ->default(false);

            $table->timestamps();

            $table->unique(
                [
                    'public_test_attempt_id',
                    'question_id',
                ],
                'public_test_answer_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_test_answers');
    }
};
