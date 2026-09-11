<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->foreignId('organisation_id')
            ->nullable()
            ->after('role_id')
            ->constrained('organisations')
            ->nullOnDelete();

        $table->foreignId('department_id')
            ->nullable()
            ->after('organisation_id')
            ->constrained('departments')
            ->nullOnDelete();
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['department_id']);
        $table->dropForeign(['organisation_id']);

        $table->dropColumn([
            'department_id',
            'organisation_id',
        ]);
    });
}
};
