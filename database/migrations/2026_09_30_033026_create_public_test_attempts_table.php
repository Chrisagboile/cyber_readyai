<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_test_attempts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('public_test_user_id')
                ->constrained('public_test_users')
                ->cascadeOnDelete();

            $table->string('status')
                ->default('in_progress');

            $table->unsignedInteger('total_questions')
                ->default(10);

            $table->unsignedInteger('answered_questions')
                ->default(0);

            $table->unsignedInteger('current_position')
                ->default(1);

            $table->unsignedInteger('correct_answers')
                ->default(0);

            $table->decimal('score_percentage', 5, 2)
                ->default(0);

            $table->string('risk_level')
                ->nullable();

            $table->timestamp('started_at')
                ->nullable();

            $table->timestamp('completed_at')
                ->nullable();

            $table->timestamps();

            $table->index([
                'public_test_user_id',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_test_attempts');
    }
};
