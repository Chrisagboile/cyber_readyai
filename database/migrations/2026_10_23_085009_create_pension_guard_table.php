<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pension_guard_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('website_url');
            $table->string('security_url')->nullable();
            $table->string('contact_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('pension_guard_accounts', function (Blueprint $table) {
            $table->id();
         $table->foreignId('user_id')->constrained('pension_guard_users')->cascadeOnDelete();
       //    $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('provider_id')->nullable()
                ->constrained('pension_guard_providers')->nullOnDelete();
            $table->string('provider_name');
            $table->string('display_name')->nullable();
            $table->string('account_type', 40);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });

        Schema::create('pension_guard_security_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pension_guard_account_id')
                ->constrained('pension_guard_accounts')->cascadeOnDelete();
            $table->string('check_key', 60);
            $table->string('title');
            $table->text('guidance');
            $table->string('official_url')->nullable();
            $table->boolean('is_complete')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(
                ['pension_guard_account_id', 'check_key'],
                'pg_checks_account_key_unique'
            );
        });

       /* Schema::create('pension_guard_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->json('answers');
            $table->unsignedSmallInteger('score');
            $table->string('risk_band', 30);
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });*/
    }

    public function down(): void
    {
        //Schema::dropIfExists('pension_guard_assessments');
        Schema::dropIfExists('pension_guard_security_checks');
        Schema::dropIfExists('pension_guard_accounts');
        Schema::dropIfExists('pension_guard_providers');
    }
};
