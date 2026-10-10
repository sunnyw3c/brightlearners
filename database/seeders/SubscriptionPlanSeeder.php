<?php

namespace Database\Seeders;

use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Enums\ProductType;
use App\Domains\Catalog\Models\Product;
use App\Domains\Membership\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $pilotPlan = SubscriptionPlan::query()->updateOrCreate(
            ['code' => 'pilot_90'],
            [
                'name' => '90-Day Family Pilot',
                'duration_days' => 90,
                'duration_months' => 3,
                'price' => 49900,
                'currency' => 'INR',
                'auto_renew_supported' => false,
                'active' => true,
                'benefits' => [
                    'member_discount_percent' => 15,
                    'inclusions' => 'All weekly learning packs for Class 1–3, parent guidance, answer keys',
                ],
            ]
        );

        SubscriptionPlan::query()->updateOrCreate(
            ['code' => 'monthly'],
            [
                'name' => 'Monthly Membership',
                'duration_days' => 30,
                'duration_months' => 1,
                'price' => 19900,
                'currency' => 'INR',
                'auto_renew_supported' => false,
                'active' => false,
                'benefits' => [
                    'member_discount_percent' => 15,
                ],
            ]
        );

        SubscriptionPlan::query()->updateOrCreate(
            ['code' => 'quarterly'],
            [
                'name' => 'Quarterly Membership',
                'duration_days' => 90,
                'duration_months' => 3,
                'price' => 54900,
                'currency' => 'INR',
                'auto_renew_supported' => false,
                'active' => false,
                'benefits' => [
                    'member_discount_percent' => 15,
                ],
            ]
        );

        SubscriptionPlan::query()->updateOrCreate(
            ['code' => 'annual'],
            [
                'name' => 'Annual Membership',
                'duration_days' => 365,
                'duration_months' => 12,
                'price' => 179900,
                'currency' => 'INR',
                'auto_renew_supported' => false,
                'active' => false,
                'benefits' => [
                    'member_discount_percent' => 15,
                ],
            ]
        );

        // Membership product linked to pilot_90 (Step 10.1)
        Product::query()->updateOrCreate(
            ['slug' => 'weekly-learning-programme-90-days'],
            [
                'name' => 'Weekly Learning Programme (90 Days)',
                'type' => ProductType::Membership,
                'subscription_plan_id' => $pilotPlan->id,
                'regular_price' => 49900,
                'currency' => 'INR',
                'status' => ProductStatus::Active,
                'short_description' => 'Structured weekly learning worksheets and guided practice across Maths, English, and EVS for 90 days.',
                'description' => 'Our 90-day family pilot delivers weekly, class-appropriate worksheet packs designed to build regular learning routines without screen dependency. Includes complete parent guidance notes and answer keys.',
                'member_discount_eligible' => false,
                'featured' => true,
                'published_at' => now(),
            ]
        );
    }
}
