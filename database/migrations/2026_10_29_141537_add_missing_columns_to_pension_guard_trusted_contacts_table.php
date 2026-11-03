
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pension_guard_trusted_contacts', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name', 120)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('relationship', 80)->nullable();

            $table->string('verification_token_hash', 64)->nullable();
            $table->timestamp('verification_expires_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();

            $table->boolean('consent_given')->default(false);
            $table->json('alert_categories')->nullable();
            $table->timestamp('consent_given_at')->nullable();
            $table->timestamp('consent_revoked_at')->nullable();
        });

        Schema::table('pension_guard_trusted_contacts', function (Blueprint $table) {
            $table->index(
                ['user_id', 'email_verified_at'],
                'pg_trusted_verification_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('pension_guard_trusted_contacts', function (Blueprint $table) {
            $table->dropIndex('pg_trusted_verification_index');

            $table->dropColumn([
                'user_id',
                'name',
                'email',
                'relationship',
                'verification_token_hash',
                'verification_expires_at',
                'email_verified_at',
                'consent_given',
                'alert_categories',
                'consent_given_at',
                'consent_revoked_at',
            ]);
        });
    }
};
