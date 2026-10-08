<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_test_learning_plans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('public_test_user_id')
                ->constrained('public_test_users')
                ->cascadeOnDelete();

            $table->foreignId('public_test_attempt_id')
                ->nullable()
                ->constrained('public_test_attempts')
                ->nullOnDelete();

            $table->string('title');

            $table->text('description')
                ->nullable();

            $table->string('priority')
                ->default('medium');

            $table->string('status')
                ->default('not_started');

            $table->unsignedTinyInteger('progress_percentage')
                ->default(0);

            $table->date('due_date')
                ->nullable();

            $table->timestamp('completed_at')
                ->nullable();

            $table->timestamps();

            $table->index('public_test_user_id');

            $table->index('public_test_attempt_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_test_learning_plans');
    }
};
