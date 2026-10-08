<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_test_feedback', function (Blueprint $table) {
            $table->id();

            $table->foreignId('public_test_attempt_id')
                ->constrained('public_test_attempts')
                ->cascadeOnDelete();

            $table->foreignId('public_test_user_id')
                ->constrained('public_test_users')
                ->cascadeOnDelete();

            $table->string('organization_name')
                ->nullable();

            $table->string('awareness_test_frequency')
                ->nullable();

            $table->json('recommended_for')
                ->nullable();

            $table->string('organization_size')
                ->nullable();

            $table->string('awareness_impact')
                ->nullable();

            $table->unsignedTinyInteger('rating');

            $table->text('comments')
                ->nullable();

            $table->text('improvement_suggestions')
                ->nullable();

            $table->timestamps();

            $table->unique(
                'public_test_attempt_id',
                'public_test_feedback_attempt_unique'
            );

            $table->index('public_test_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_test_feedback');
    }
};
