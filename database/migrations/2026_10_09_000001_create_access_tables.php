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
        if (! Schema::hasTable('entitlements')) {
            Schema::create('entitlements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('learning_profile_id')->nullable()->constrained('learning_profiles')->nullOnDelete();
                $table->foreignId('resource_id')->constrained('resources')->cascadeOnDelete();
                $table->string('source_type'); // 'order_item', 'admin_grant'
                $table->unsignedBigInteger('source_id')->nullable();
                $table->timestamp('starts_at');
                $table->timestamp('ends_at')->nullable();
                $table->timestamp('revoked_at')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->unique(['user_id', 'resource_id', 'source_type', 'source_id'], 'entitlements_unique_source');
                $table->index(['user_id', 'resource_id']);
            });
        }

        if (! Schema::hasTable('downloads')) {
            Schema::create('downloads', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('learning_profile_id')->nullable()->constrained('learning_profiles')->nullOnDelete();
                $table->foreignId('resource_id')->constrained('resources')->cascadeOnDelete();
                $table->foreignId('resource_version_id')->constrained('resource_versions')->cascadeOnDelete();
                $table->foreignId('entitlement_id')->nullable()->constrained('entitlements')->nullOnDelete();
                $table->string('access_source')->default('free'); // 'free', 'purchase', 'membership', 'admin_grant'
                $table->string('variant')->default('colour'); // 'colour', 'low_ink', 'answer_key'
                $table->string('ip_hash')->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamp('downloaded_at');
                $table->timestamps();

                $table->index('resource_version_id');
                $table->index(['user_id', 'downloaded_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downloads');
        Schema::dropIfExists('entitlements');
    }
};
