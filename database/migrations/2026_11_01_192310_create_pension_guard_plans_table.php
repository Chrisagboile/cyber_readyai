<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pension_guard_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            $table->string('billing_interval', 20)->nullable();
            $table->unsignedInteger('amount_pence')->default(0);
            $table->char('currency', 3)->default('gbp');

            $table->string('stripe_price_id')->nullable()->unique();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_free')->default(false);

            $table->json('features')->nullable();
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pension_guard_plans');
    }
};
