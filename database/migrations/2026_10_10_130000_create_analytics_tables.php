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
        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_name')->index();
            $table->string('anonymous_id')->nullable()->index();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('session_id')->nullable();
            $table->timestamp('occurred_at')->index();
            $table->json('properties');
            $table->string('source')->default('server');
            $table->string('dedupe_key')->nullable()->unique();
            $table->timestamps();

            $table->index(['event_name', 'occurred_at']);
        });

        Schema::create('daily_metrics', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->string('metric_key')->index();
            $table->json('dimension')->nullable();
            $table->string('dimension_hash', 64)->default('');
            $table->decimal('value', 20, 4);
            $table->timestamps();

            $table->unique(['date', 'metric_key', 'dimension_hash']);
        });

        Schema::create('acquisition_sources', function (Blueprint $table) {
            $table->id();
            $table->string('anonymous_id')->nullable()->index();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_term')->nullable();
            $table->string('utm_content')->nullable();
            $table->string('referrer')->nullable();
            $table->string('landing_page')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acquisition_sources');
        Schema::dropIfExists('daily_metrics');
        Schema::dropIfExists('analytics_events');
    }
};
