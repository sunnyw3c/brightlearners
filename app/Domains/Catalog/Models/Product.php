<?php

namespace App\Domains\Catalog\Models;

use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Enums\ProductType;
use App\Domains\Catalog\Policies\ProductPolicy;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Support\Concerns\Auditable;
use Database\Factories\ProductFactory;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Scout\Builder as ScoutBuilder;
use Laravel\Scout\Searchable;

/**
 * The sellable offer. A product points at one or more resources
 * (`product_resources`) or, for a bundle, at other products
 * (`bundle_products`). It never copies a file
 * (docs/plan/phase-06-product-catalogue.md).
 */
#[Fillable([
    'name', 'slug', 'type', 'sku', 'short_description', 'description',
    'primary_class_id', 'regular_price', 'sale_price', 'sale_starts_at',
    'sale_ends_at', 'currency', 'member_discount_eligible', 'status',
    'featured', 'cover_path', 'publish_at', 'published_at', 'seo_title',
    'seo_description',
])]
#[UseFactory(ProductFactory::class)]
#[UsePolicy(ProductPolicy::class)]
class Product extends Model
{
    use Auditable;

    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    use Searchable;
    use SoftDeletes;

    /**
     * Columns only a holder of `products.edit-price` may change, enforced
     * here so a crafted request cannot bypass the Filament form
     * (docs/plan/phase-06-product-catalogue.md, "Admin (Filament)").
     *
     * @var list<string>
     */
    private const PRICE_COLUMNS = ['regular_price', 'sale_price', 'sale_starts_at', 'sale_ends_at'];

    protected static function booted(): void
    {
        static::saving(function (Product $product): void {
            if ($product->sale_price !== null && $product->sale_price >= $product->regular_price) {
                throw ValidationException::withMessages([
                    'sale_price' => 'The sale price must be lower than the regular price.',
                ]);
            }
        });

        static::updating(function (Product $product): void {
            $user = Auth::user();

            if ($user === null) {
                return;
            }

            $priceChanged = array_intersect(self::PRICE_COLUMNS, array_keys($product->getDirty())) !== [];

            if ($priceChanged && ! $user->can('products.edit-price')) {
                throw new AuthorizationException('Only a role with products.edit-price can change a live price.');
            }
        });
    }

    /**
     * The `database` Scout driver queries this table directly, so this is
     * the constraint that actually keeps a draft or archived product out
     * of search results (mirrors `LearningResource::newScoutQuery()`).
     *
     * @param  Builder<Product>  $builder
     * @return Builder<Product>
     */
    public function newScoutQuery(ScoutBuilder $builder): Builder
    {
        return static::query()->active();
    }

    public function shouldBeSearchable(): bool
    {
        return $this->status === ProductStatus::Active;
    }

    /**
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'short_description' => (string) $this->short_description,
            'description' => (string) $this->description,
        ];
    }

    /**
     * @return BelongsTo<SchoolClass, $this>
     */
    public function primaryClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'primary_class_id');
    }

    /**
     * The resources this product includes, in merchandising order
     * (step 6.2).
     *
     * @return BelongsToMany<LearningResource, $this, ProductResource>
     */
    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(LearningResource::class, 'product_resources', 'product_id', 'resource_id')
            ->using(ProductResource::class)
            ->withPivot(['sort_order', 'version_policy'])
            ->withTimestamps()
            ->orderBy('product_resources.sort_order');
    }

    /**
     * A bundle's child products, in merchandising order (step 6.3).
     *
     * @return BelongsToMany<Product, $this, BundleProduct>
     */
    public function childProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'bundle_products', 'bundle_id', 'product_id')
            ->using(BundleProduct::class)
            ->withPivot(['sort_order'])
            ->withTimestamps()
            ->orderBy('bundle_products.sort_order');
    }

    /**
     * The bundles that include this product — the upsell shown on its
     * own page (step 6.8).
     *
     * @return BelongsToMany<Product, $this, BundleProduct>
     */
    public function bundlesContaining(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'bundle_products', 'product_id', 'bundle_id')
            ->using(BundleProduct::class)
            ->withTimestamps();
    }

    /**
     * For a normal product, its own resources. For a bundle, the union of
     * every child product's resources, without duplicates (step 6.3).
     * Phase 9 uses this to grant access.
     *
     * @return Collection<int, LearningResource>
     */
    public function deliverableResources(): Collection
    {
        if ($this->type === ProductType::Bundle) {
            return $this->childProducts()
                ->with('resources')
                ->get()
                ->flatMap(fn (Product $child): Collection => $child->resources)
                ->unique('id')
                ->values();
        }

        return $this->resources;
    }

    /**
     * The sum of the included resources' page counts (step 6.7).
     */
    public function totalPages(): int
    {
        return (int) $this->deliverableResources()->sum('page_count');
    }

    /**
     * Whether this product may become `active` (step 6.5):
     * - a normal product needs at least one resource with a published
     *   current version that has a real preview page;
     * - a bundle needs at least two active child products;
     * - a membership product (Phase 10) is not handled here.
     */
    public function meetsActivationRequirements(): bool
    {
        if ($this->type === ProductType::Bundle) {
            return $this->childProducts()
                ->where('status', ProductStatus::Active->value)
                ->count() >= 2;
        }

        return $this->resources->contains(function (LearningResource $resource): bool {
            $version = $resource->currentVersion;

            return $resource->status === ResourceStatus::Published
                && $version !== null
                && $version->previews()->exists();
        });
    }

    /**
     * The `{class}` segment of the canonical `/shop/{class}/{slug}` URL.
     * Flagship products with no primary class (step 6.10) use
     * `all-classes`.
     */
    public function classSlug(): string
    {
        return $this->primaryClass?->slug ?? 'all-classes';
    }

    /**
     * @param  Builder<Product>  $query
     * @return Builder<Product>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('status'), ProductStatus::Active->value);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => ProductType::class,
            'status' => ProductStatus::class,
            'member_discount_eligible' => 'boolean',
            'featured' => 'boolean',
            'sale_starts_at' => 'datetime',
            'sale_ends_at' => 'datetime',
            'publish_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }
}
