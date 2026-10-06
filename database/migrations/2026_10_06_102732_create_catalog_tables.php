<?php

use App\Domains\Catalog\Enums\ProductStatus;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type')->index();
            $table->string('sku')->nullable()->unique();
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            /*
             * Added beyond the source plan: gives the `{class}` segment of
             * the canonical `/shop/{class}/{slug}` URL and the class filter
             * (docs/reference/database-blueprint.md, "Catalogue — Phase 6").
             */
            $table->foreignId('primary_class_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->unsignedInteger('regular_price');
            $table->unsignedInteger('sale_price')->nullable();
            $table->timestamp('sale_starts_at')->nullable();
            $table->timestamp('sale_ends_at')->nullable();
            $table->char('currency', 3)->default('INR');
            $table->boolean('member_discount_eligible')->default(false);
            $table->string('status')->default(ProductStatus::Draft->value);
            $table->boolean('featured')->default(false);
            $table->string('cover_path')->nullable();
            $table->timestamp('publish_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'type']);
        });

        Schema::create('product_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('resource_id')->constrained('resources')->cascadeOnDelete();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('version_policy')->default('current');
            $table->timestamps();

            $table->unique(['product_id', 'resource_id']);
        });

        Schema::create('bundle_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundle_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['bundle_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bundle_products');
        Schema::dropIfExists('product_resources');
        Schema::dropIfExists('products');
    }
};
