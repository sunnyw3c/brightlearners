<?php

namespace App\Domains\Membership\Models;

use App\Domains\Catalog\Models\Product;
use Database\Factories\SubscriptionPlanFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'code', 'name', 'duration_days', 'duration_months', 'price',
    'currency', 'auto_renew_supported', 'active', 'benefits',
])]
#[UseFactory(SubscriptionPlanFactory::class)]
class SubscriptionPlan extends Model
{
    /** @use HasFactory<SubscriptionPlanFactory> */
    use HasFactory;

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
