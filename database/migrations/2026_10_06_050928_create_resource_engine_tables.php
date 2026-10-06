<?php

use App\Domains\Content\Enums\PreviewStatus;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Enums\ReviewStatus;
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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('type')->index();
            $table->string('summary', 300)->nullable();
            $table->text('description')->nullable();
            $table->text('learning_objective')->nullable();
            $table->string('difficulty')->nullable();
            $table->unsignedSmallInteger('estimated_minutes')->nullable();
            $table->unsignedSmallInteger('page_count')->nullable();
            $table->text('supplies')->nullable();
            $table->string('language', 10)->default('en');
            $table->boolean('has_answer_key')->default(false);
            $table->boolean('low_ink_available')->default(false);
            $table->string('licence_type')->default('household');
            $table->boolean('is_free')->default(false)->index();
            $table->boolean('ai_assisted')->default(false);
            $table->boolean('featured')->default(false);
            $table->string('status')->default(ResourceStatus::Draft->value);
            $table->timestamp('scheduled_for')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'published_at']);
        });

        Schema::create('resource_skill', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')->constrained('resources')->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->unique(['resource_id', 'skill_id', 'class_id']);
            $table->index(['class_id', 'skill_id']);
        });

        Schema::create('resource_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')->constrained('resources')->cascadeOnDelete();
            $table->string('version', 10);
            $table->string('file_path');
            $table->string('low_ink_path')->nullable();
            $table->string('answer_file_path')->nullable();
            $table->string('checksum', 64)->nullable();
            $table->text('change_notes')->nullable();
            $table->string('preview_status')->default(PreviewStatus::Pending->value);
            /*
             * Added beyond the source plan: staged correction metadata,
             * written on "create correction" (step 4.9) and promoted into
             * a resource_corrections row when this version is published.
             * See docs/reference/database-blueprint.md.
             */
            $table->string('correction_severity')->nullable();
            $table->boolean('customer_notice_required')->default(false);
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_current')->default(false);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->unique(['resource_id', 'version']);
        });

        Schema::create('resource_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')->constrained('resources')->cascadeOnDelete();
            $table->foreignId('resource_version_id')->constrained()->cascadeOnDelete();
            $table->string('review_type');
            $table->foreignId('reviewer_id')->constrained('users');
            $table->string('status')->default(ReviewStatus::Pending->value);
            $table->text('notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['resource_version_id', 'review_type']);
        });

        Schema::create('resource_previews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_version_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('page_no');
            $table->string('image_path');
            $table->unsignedSmallInteger('width');
            $table->unsignedSmallInteger('height');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('resource_corrections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')->constrained('resources')->cascadeOnDelete();
            $table->string('version_from');
            $table->string('version_to');
            $table->string('severity');
            $table->boolean('customer_notice_required')->default(false);
            $table->text('notes')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_corrections');
        Schema::dropIfExists('resource_previews');
        Schema::dropIfExists('resource_reviews');
        Schema::dropIfExists('resource_versions');
        Schema::dropIfExists('resource_skill');
        Schema::dropIfExists('resources');
    }
};
