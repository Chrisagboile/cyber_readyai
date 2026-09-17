<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->foreignId('organisation_id')
                ->nullable()
                ->after('created_by')
                ->constrained('organisations')
                ->nullOnDelete();

            $table->foreignId('department_id')
                ->nullable()
                ->after('organisation_id')
                ->constrained('departments')
                ->nullOnDelete();

            $table->index([
                'organisation_id',
                'department_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropForeign([
                'organisation_id',
            ]);

            $table->dropForeign([
                'department_id',
            ]);

            $table->dropIndex([
                'assessments_organisation_id_department_id_index',
            ]);

            $table->dropColumn([
                'organisation_id',
                'department_id',
            ]);
        });
    }
};
