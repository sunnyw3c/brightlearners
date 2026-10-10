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
        if (! Schema::hasTable('subscription_plans')) {
            Schema::create('subscription_plans', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('name');
                $table->unsignedInteger('duration_days')->nullable();
                $table->unsignedInteger('duration_months')->nullable();
                $table->unsignedInteger('price'); // paise
                $table->char('currency', 3)->default('INR');
                $table->boolean('auto_renew_supported')->default(false);
                $table->boolean('active')->default(false);
                $table->json('benefits')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('products') && ! Schema::hasColumn('products', 'subscription_plan_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->foreignId('subscription_plan_id')->nullable()->after('primary_class_id')->constrained('subscription_plans')->nullOnDelete();
            });
        }

        if (! Schema::hasTable('subscriptions')) {
            Schema::create('subscriptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('plan_id')->constrained('subscription_plans')->cascadeOnDelete();
                $table->foreignId('source_order_id')->nullable()->unique()->constrained('orders')->nullOnDelete();
                $table->timestamp('starts_at');
                $table->timestamp('ends_at');
                $table->string('status')->default('active');
                $table->boolean('auto_renew')->default(false);
                $table->timestamp('cancelled_at')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'status']);
                $table->index(['status', 'ends_at']);
            });
        }

        if (! Schema::hasTable('subscription_events')) {
            Schema::create('subscription_events', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subscription_id')->constrained('subscriptions')->cascadeOnDelete();
                $table->string('type');
                $table->timestamp('occurred_at');
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->index(['subscription_id', 'occurred_at']);
            });
        }

        if (! Schema::hasTable('learning_programs')) {
            Schema::create('learning_programs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
                $table->unsignedTinyInteger('month');
                $table->unsignedSmallInteger('year');
                $table->string('title');
                $table->string('status')->default('draft');
                $table->timestamp('publish_at')->nullable();
                $table->timestamps();

                $table->unique(['class_id', 'year', 'month'], 'learning_programs_class_year_month_unique');
            });
        }

        if (! Schema::hasTable('learning_weeks')) {
            Schema::create('learning_weeks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('program_id')->constrained('learning_programs')->cascadeOnDelete();
                $table->unsignedTinyInteger('week_number');
                $table->string('title');
                $table->string('emphasis')->nullable();
                $table->timestamp('release_at');
                $table->timestamp('released_at')->nullable();
                $table->timestamps();

                $table->unique(['program_id', 'week_number'], 'learning_weeks_program_week_unique');
                $table->index(['release_at', 'released_at']);
            });
        }

        if (! Schema::hasTable('learning_items')) {
            Schema::create('learning_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('week_id')->constrained('learning_weeks')->cascadeOnDelete();
                $table->foreignId('resource_id')->constrained('resources')->cascadeOnDelete();
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('required')->default(true);
                $table->text('guidance')->nullable();
                $table->timestamps();

                $table->unique(['week_id', 'resource_id'], 'learning_items_week_resource_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_items');
        Schema::dropIfExists('learning_weeks');
        Schema::dropIfExists('learning_programs');
        Schema::dropIfExists('subscription_events');
        Schema::dropIfExists('subscriptions');

        if (Schema::hasTable('products') && Schema::hasColumn('products', 'subscription_plan_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropForeign(['subscription_plan_id']);
                $table->dropColumn('subscription_plan_id');
            });
        }

        Schema::dropIfExists('subscription_plans');
    }
};
