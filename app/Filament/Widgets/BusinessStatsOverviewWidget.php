<?php

namespace App\Filament\Widgets;

use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Models\Order;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Membership\Models\Subscription;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BusinessStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalPaise = Order::query()->where('status', 'paid')->sum('total');
        $formattedRevenue = '₹'.number_format($totalPaise / 100, 2);

        $thisMonthPaise = Order::query()
            ->where('status', 'paid')
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('total');
        $thisMonthRevenue = '₹'.number_format($thisMonthPaise / 100, 2);

        $totalOrders = Order::query()->count();
        $paidOrders = Order::query()->where('status', 'paid')->count();

        $activeSubscriptions = Subscription::query()
            ->where('status', 'active')
            ->count();

        $activeProducts = Product::query()
            ->where('status', ProductStatus::Active)
            ->count();
        $totalProducts = Product::query()->count();

        $publishedResources = LearningResource::query()
            ->where('status', ResourceStatus::Published)
            ->count();
        $draftResources = LearningResource::query()
            ->where('status', ResourceStatus::Draft)
            ->count();

        $parentCount = User::query()->whereDoesntHave('roles')->count();

        return [
            Stat::make('Total Revenue', $formattedRevenue)
                ->description("This month: {$thisMonthRevenue}")
                ->descriptionIcon('heroicon-m-currency-rupee')
                ->color('success'),

            Stat::make('Orders Placed', (string) $totalOrders)
                ->description("{$paidOrders} completed payments")
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('primary')
                ->url('/admin/orders'),

            Stat::make('Active Memberships', (string) $activeSubscriptions)
                ->description('Active recurring & pilot members')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('warning')
                ->url('/admin/subscriptions'),

            Stat::make('Products in Catalog', "{$activeProducts} active")
                ->description("{$totalProducts} total SKUs")
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('info')
                ->url('/admin/products'),

            Stat::make('Learning Resources', (string) $publishedResources)
                ->description("{$draftResources} drafts in progress")
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success')
                ->url('/admin/learning-resources'),

            Stat::make('Registered Parents', (string) $parentCount)
                ->description('Active family accounts')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary')
                ->url('/admin/customers'),
        ];
    }
}
