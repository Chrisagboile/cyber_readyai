<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pension_guard_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('pension_guard_users')
                ->cascadeOnDelete();

            $table->foreignId('subscription_id')
                ->nullable()
                ->constrained('pension_guard_subscriptions')
                ->nullOnDelete();

            $table->string('stripe_invoice_id')->nullable()->unique();
            $table->string('stripe_payment_intent_id')->nullable()->index();

            $table->unsignedInteger('amount_pence')->default(0);
            $table->char('currency', 3)->default('gbp');

            $table->string('status', 30)->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pension_guard_payments');
    }
};
