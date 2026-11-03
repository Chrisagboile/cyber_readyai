
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pension_guard_accounts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('pension_guard_assessments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('pension_guard_accounts', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('pension_guard_users')
                ->cascadeOnDelete();
        });

        Schema::table('pension_guard_assessments', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('pension_guard_users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pension_guard_accounts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('pension_guard_assessments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('pension_guard_accounts', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });

        Schema::table('pension_guard_assessments', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }
};
