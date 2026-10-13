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
        if (! Schema::hasTable('assessments')) {
            Schema::create('assessments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
                $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('status')->default('draft');
                $table->unsignedInteger('version')->default(1);
                $table->timestamps();

                $table->index(['class_id', 'subject_id', 'status']);
            });
        }

        if (! Schema::hasTable('assessment_sections')) {
            Schema::create('assessment_sections', function (Blueprint $table) {
                $table->id();
                $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
                $table->string('title');
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();

                $table->index(['assessment_id', 'sort_order']);
            });
        }

        if (! Schema::hasTable('assessment_questions')) {
            Schema::create('assessment_questions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('section_id')->constrained('assessment_sections')->cascadeOnDelete();
                $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete();
                $table->string('type')->default('single_choice');
                $table->text('prompt');
                $table->string('difficulty')->default('medium');
                $table->json('answer_config');
                $table->unsignedInteger('points')->default(1);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();

                $table->index(['section_id', 'sort_order']);
                $table->index('skill_id');
            });
        }

        if (! Schema::hasTable('assessment_attempts')) {
            Schema::create('assessment_attempts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('learning_profile_id')->constrained('learning_profiles')->cascadeOnDelete();
                $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
                $table->unsignedInteger('assessment_version')->default(1);
                $table->string('status')->default('in_progress');
                $table->string('parent_feedback')->nullable();
                $table->timestamp('started_at');
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();

                $table->index(['learning_profile_id', 'assessment_id']);
                $table->index(['user_id', 'status']);
            });
        }

        if (! Schema::hasTable('assessment_answers')) {
            Schema::create('assessment_answers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('attempt_id')->constrained('assessment_attempts')->cascadeOnDelete();
                $table->foreignId('question_id')->constrained('assessment_questions')->cascadeOnDelete();
                $table->json('response');
                $table->boolean('is_correct')->default(false);
                $table->unsignedInteger('score')->default(0);
                $table->timestamps();

                $table->unique(['attempt_id', 'question_id']);
            });
        }

        if (! Schema::hasTable('skill_scores')) {
            Schema::create('skill_scores', function (Blueprint $table) {
                $table->id();
                $table->foreignId('learning_profile_id')->constrained('learning_profiles')->cascadeOnDelete();
                $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete();
                $table->unsignedInteger('score')->default(0);
                $table->string('band');
                $table->foreignId('source_attempt_id')->constrained('assessment_attempts')->cascadeOnDelete();
                $table->timestamp('calculated_at');
                $table->timestamps();

                $table->index(['learning_profile_id', 'skill_id']);
            });
        }

        if (! Schema::hasTable('recommendations')) {
            Schema::create('recommendations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('learning_profile_id')->constrained('learning_profiles')->cascadeOnDelete();
                $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete();
                $table->string('target_type');
                $table->unsignedBigInteger('target_id');
                $table->string('reason_code');
                $table->timestamp('generated_at');
                $table->timestamp('clicked_at')->nullable();
                $table->timestamp('acted_at')->nullable();
                $table->timestamps();

                $table->index(['learning_profile_id', 'skill_id']);
                $table->index(['learning_profile_id', 'target_type', 'target_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendations');
        Schema::dropIfExists('skill_scores');
        Schema::dropIfExists('assessment_answers');
        Schema::dropIfExists('assessment_attempts');
        Schema::dropIfExists('assessment_questions');
        Schema::dropIfExists('assessment_sections');
        Schema::dropIfExists('assessments');
    }
};
