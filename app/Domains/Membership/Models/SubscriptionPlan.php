<?php

namespace App\Domains\Membership\Models;

use App\Domains\Catalog\Models\Product;
use App\Domains\Membership\Policies\SubscriptionPlanPolicy;
use App\Support\Concerns\Auditable;
use Database\Factories\SubscriptionPlanFactory;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

#[Fillable([
    'code', 'name', 'duration_days', 'duration_months', 'price',
    'currency', 'auto_renew_supported', 'active', 'benefits',
])]
#[UseFactory(SubscriptionPlanFactory::class)]
#[UsePolicy(SubscriptionPlanPolicy::class)]
class SubscriptionPlan extends Model
{
    use Auditable;

    /** @use HasFactory<SubscriptionPlanFactory> */
    use HasFactory;

    /**
     * Plan pricing is `business-admin` only (Phase 10, "Admin"). Checked
     * again here, as `Product::booted()` does for product prices, so a
     * crafted request cannot skip the policy. Seeders and console jobs
     * run without a user and are not blocked.
     */
    protected static function booted(): void
    {
        static::updating(function (SubscriptionPlan $plan): void {
            $user = Auth::user();

            if ($user !== null && $plan->isDirty('price') && ! $user->can('plans.edit-price')) {
                throw new AuthorizationException('Only a role with plans.edit-price can change a plan price.');
            }
        });
    }

    /**
     * @return HasMany<Subscription, $this>
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    /**
     * @return HasMany<Product, $this>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'subscription_plan_id');
    }

    protected function casts(): array
    {
        return [
            'duration_days' => 'integer',
            'duration_months' => 'integer',
            'price' => 'integer',
            'auto_renew_supported' => 'boolean',
            'active' => 'boolean',
            'benefits' => 'array',
        ];
    }
}
