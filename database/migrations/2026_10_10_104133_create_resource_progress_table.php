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
        if (! Schema::hasTable('resource_progress')) {
            Schema::create('resource_progress', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('learning_profile_id')->constrained('learning_profiles')->cascadeOnDelete();
                $table->foreignId('resource_id')->constrained('resources')->cascadeOnDelete();
                $table->string('status')->default('not_started');
                $table->timestamp('opened_at')->nullable();
                $table->timestamp('downloaded_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->string('source_context')->nullable();
                $table->timestamps();

                $table->unique(['learning_profile_id', 'resource_id']);
                $table->index(['user_id', 'status']);
                $table->index(['learning_profile_id', 'status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_progress');
    }
};
