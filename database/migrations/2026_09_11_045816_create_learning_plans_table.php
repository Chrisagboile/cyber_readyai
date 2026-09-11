<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_plans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('assessment_attempt_id')
                ->nullable()
                ->constrained('assessment_attempts')
                ->nullOnDelete();

            $table->string('title');

            $table->text('description')->nullable();

            $table->string('priority')->default('medium');

            $table->string('status')->default('not_started');

            $table->unsignedInteger('progress_percentage')->default(0);

            $table->date('due_date')->nullable();

            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_plans');
    }
};
