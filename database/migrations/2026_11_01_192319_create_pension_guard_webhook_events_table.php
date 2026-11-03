<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pension_guard_webhook_events', function (Blueprint $table) {
            $table->id();

            $table->string('stripe_event_id')->unique();
            $table->string('event_type');
            $table->string('processing_status', 20)->default('pending');

            $table->timestamp('processed_at')->nullable();
            $table->text('failure_message')->nullable();

            $table->timestamps();

            $table->index(['event_type', 'processing_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pension_guard_webhook_events');
    }
};
