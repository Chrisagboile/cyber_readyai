
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phishing_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('name');
            $table->string('category')->default('general');
            $table->string('subject');
            $table->longText('body');
            $table->longText('landing_page_content')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('phishing_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')
                ->nullable()
                ->constrained('phishing_templates')
                ->nullOnDelete();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'scheduled_at']);
        });

        Schema::create('phishing_campaign_recipients', function (Blueprint $table) {
            $table->id();

            $table->foreignId('campaign_id')
                ->constrained('phishing_campaigns')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('status')->default('pending');
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->timestamp('landing_page_visited_at')->nullable();
            $table->timestamp('reported_at')->nullable();
            $table->timestamps();

            $table->unique(['campaign_id', 'user_id']);
            $table->index(['campaign_id', 'status']);
        });

        Schema::create('phishing_events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('recipient_id')
                ->constrained('phishing_campaign_recipients')
                ->cascadeOnDelete();

            $table->string('event_type');
            $table->json('event_data')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();

            $table->index(['recipient_id', 'event_type']);
            $table->index('occurred_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phishing_events');
        Schema::dropIfExists('phishing_campaign_recipients');
        Schema::dropIfExists('phishing_campaigns');
        Schema::dropIfExists('phishing_templates');
    }
};
