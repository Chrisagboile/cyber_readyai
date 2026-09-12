<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organisation_id')
                ->constrained('organisations')
                ->cascadeOnDelete();

            $table->string('plan_name');

            $table->string('status')
                ->default('active');

            $table->string('billing_interval')
                ->default('monthly');

            $table->decimal('price', 10, 2)
                ->default(0);

            $table->unsignedInteger('max_users')
                ->nullable();

            $table->unsignedInteger('max_assessments')
                ->nullable();

            $table->date('starts_at');

            $table->date('ends_at')
                ->nullable();

            $table->date('trial_ends_at')
                ->nullable();

            $table->date('cancelled_at')
                ->nullable();

            $table->text('notes')
                ->nullable();

            $table->timestamps();

            $table->index(['organisation_id', 'status']);
            $table->index('ends_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
