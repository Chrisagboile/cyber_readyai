<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_questions', function (Blueprint $table) {
            $table->foreignId('assessment_id')
                ->nullable()
                ->after('employee_id')
                ->constrained('assessments')
                ->cascadeOnDelete();

            $table->index([
                'assessment_id',
                'question_order',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('employee_questions', function (Blueprint $table) {
            $table->dropForeign([
                'assessment_id',
            ]);

            $table->dropIndex([
                'assessment_id',
                'question_order',
            ]);

            $table->dropColumn('assessment_id');
        });
    }
};
