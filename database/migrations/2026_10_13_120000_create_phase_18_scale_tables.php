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
        // 1. Organisations & Teacher Licensing
        if (! Schema::hasTable('organisations')) {
            Schema::create('organisations', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('type')->default('school'); // school, coaching_centre, teacher_group
                $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('organisation_members')) {
            Schema::create('organisation_members', function (Blueprint $table) {
                $table->id();
                $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('role')->default('teacher'); // owner, admin, teacher
                $table->timestamp('joined_at');
                $table->timestamps();

                $table->unique(['organisation_id', 'user_id']);
            });
        }

        if (! Schema::hasTable('classroom_licences')) {
            Schema::create('classroom_licences', function (Blueprint $table) {
                $table->id();
                $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();
                $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
                $table->string('name'); // e.g. "Class 4B Maths & Science"
                $table->unsignedInteger('seat_limit')->default(35);
                $table->boolean('printable_rights')->default(true);
                $table->boolean('redistribution_terms_accepted')->default(true);
                $table->timestamp('starts_at');
                $table->timestamp('ends_at');
                $table->string('status')->default('active');
                $table->timestamps();

                $table->index(['teacher_id', 'status']);
                $table->index(['organisation_id', 'status']);
            });
        }

        // 2. Interactive Learning & Auto-Grading
        if (! Schema::hasTable('interactive_exercises')) {
            Schema::create('interactive_exercises', function (Blueprint $table) {
                $table->id();
                $table->foreignId('resource_id')->nullable()->constrained('resources')->nullOnDelete();
                $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete();
                $table->string('title');
                $table->string('slug')->unique();
                $table->json('question_config'); // array of questions with prompts, choices, correct values, feedback
                $table->boolean('active')->default(true);
                $table->timestamps();

                $table->index(['skill_id', 'active']);
            });
        }

        // 3. AI Parent Guidance with Safeguards
        if (! Schema::hasTable('parent_guidance_logs')) {
            Schema::create('parent_guidance_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('profile_id')->nullable()->constrained('learning_profiles')->nullOnDelete();
                $table->text('query');
                $table->text('guidance');
                $table->json('safety_flags')->nullable();
                $table->boolean('escalated')->default(false);
                $table->timestamps();

                $table->index(['user_id', 'created_at']);
            });
        }

        // 4. API Tokens for Versioned Sanctum/Bearer API
        if (! Schema::hasTable('api_tokens')) {
            Schema::create('api_tokens', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('name');
                $table->string('token', 64)->unique();
                $table->json('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'token']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_tokens');
        Schema::dropIfExists('parent_guidance_logs');
        Schema::dropIfExists('interactive_exercises');
        Schema::dropIfExists('classroom_licences');
        Schema::dropIfExists('organisation_members');
        Schema::dropIfExists('organisations');
    }
};
