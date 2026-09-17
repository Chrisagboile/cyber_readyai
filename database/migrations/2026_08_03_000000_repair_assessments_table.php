<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            if (! Schema::hasColumn('assessments', 'name')) {
                $table->string('name')->after('id');
            }

            if (! Schema::hasColumn('assessments', 'created_by')) {
                $table->foreignId('created_by')
                    ->constrained('users')
                    ->cascadeOnDelete();
            }

            if (! Schema::hasColumn('assessments', 'employee_id')) {
                $table->foreignId('employee_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('assessments', 'status')) {
                $table->string('status', 30)
                    ->default('active');
            }

            if (! Schema::hasColumn('assessments', 'total_questions')) {
                $table->unsignedInteger('total_questions')
                    ->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            if (Schema::hasColumn('assessments', 'created_by')) {
                $table->dropForeign(['created_by']);
            }

            if (Schema::hasColumn('assessments', 'employee_id')) {
                $table->dropForeign(['employee_id']);
            }

            $columns = [];

            foreach ([
                'name',
                'created_by',
                'employee_id',
                'status',
                'total_questions',
            ] as $column) {
                if (Schema::hasColumn('assessments', $column)) {
                    $columns[] = $column;
                }
            }

            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
};
