<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessment_attempts', function (Blueprint $table) {

            $table->foreignId('assessment_id')
                ->after('id')
                ->constrained('assessments')
                ->cascadeOnDelete();

            $table->unsignedInteger('current_position')
                ->default(0)
                ->after('assessment_id');

            $table->timestamp('expires_at')
                ->nullable()
                ->after('started_at');

            $table->unique([
                'assessment_id',
                'user_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('assessment_attempts', function (Blueprint $table) {

            $table->dropUnique([
                'assessment_id',
                'user_id'
            ]);

            $table->dropForeign([
                'assessment_id'
            ]);

            $table->dropColumn([
                'assessment_id',
                'current_position',
                'expires_at',
            ]);
        });
    }
};
