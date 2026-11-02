
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pension_guard_security_incidents', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('affected_organisation', 200)->nullable();
            $table->text('description');
            $table->string('source_name', 200);
            $table->string('source_url', 2048);
            $table->date('reported_at')->nullable();
            $table->string('status', 30)->default('unverified')->index();
            $table->text('review_notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->timestamps();

            $table->index(['status', 'reported_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pension_guard_security_incidents');
    }
};
