<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_test_questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('public_test_attempt_id')
                ->constrained('public_test_attempts')
                ->cascadeOnDelete();

            /*
             * This intentionally points to the main application's
             * question bank.
             */
            $table->foreignId('question_id')
                ->constrained('questions')
                ->restrictOnDelete();

            $table->unsignedInteger('question_order');

            $table->timestamps();

            $table->unique(
                [
                    'public_test_attempt_id',
                    'question_id',
                ],
                'public_test_attempt_question_unique'
            );

            $table->unique(
                [
                    'public_test_attempt_id',
                    'question_order',
                ],
                'public_test_attempt_order_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_test_questions');
    }
};
