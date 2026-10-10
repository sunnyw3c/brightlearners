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
        Schema::create('article_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('body');
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('article_categories')->cascadeOnDelete();
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('og_image_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('article_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
            $table->string('related_type');
            $table->unsignedBigInteger('related_id');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['related_type', 'related_id']);
            $table->index(['article_id', 'sort_order']);
        });

        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            $table->string('from_path')->unique();
            $table->string('to_path');
            $table->unsignedSmallInteger('status_code')->default(301);
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('hits')->default(0);
            $table->timestamps();
        });

        Schema::table('resources', function (Blueprint $table) {
            $table->string('seo_title')->nullable()->after('published_at');
            $table->text('seo_description')->nullable()->after('seo_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn(['seo_title', 'seo_description']);
        });
        Schema::dropIfExists('redirects');
        Schema::dropIfExists('article_relations');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('article_categories');
    }
};
