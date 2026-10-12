<?php

namespace App\Filament\Widgets;

use App\Domains\Content\Models\ResourceCorrection;
use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Membership\Models\LearningWeek;
use App\Domains\Membership\Models\Subscription;
use App\Domains\Payments\Enums\PaymentStatus;
use App\Domains\Payments\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OperationsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $reviewCount = ResourceVersion::query()
            ->whereNull('published_at')
            ->count();

        $missingItemsCount = LearningWeek::query()
            ->whereDoesntHave('items')
            ->count();

        $failedPaymentsCount = Payment::query()
            ->where('status', PaymentStatus::Failed)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $expiringMembersCount = Subscription::query()
            ->where('status', 'active')
            ->whereBetween('ends_at', [now(), now()->addDays(7)])
            ->count();

        $pendingNoticesCount = ResourceCorrection::query()
            ->where('customer_notice_required', true)
            ->whereNull('notified_at')
            ->count();

        return [
            Stat::make('Content Awaiting Review', $reviewCount)
                ->description('Unpublished resource versions')
                ->descriptionIcon('heroicon-m-clock')
                ->color($reviewCount > 0 ? 'warning' : 'success')
                ->url('/admin/review-queue'),

            Stat::make('Programmes Missing Items', $missingItemsCount)
                ->description('Learning weeks with 0 items')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color($missingItemsCount > 0 ? 'danger' : 'success')
                ->url('/admin/learning-programs'),

            Stat::make('Failed Payments (7d)', $failedPaymentsCount)
                ->description('Recent payment failures')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color($failedPaymentsCount > 0 ? 'danger' : 'success')
                ->url('/admin/payments'),

            Stat::make('Expiring Memberships (7d)', $expiringMembersCount)
                ->description('Active expiring within 7 days')
                ->descriptionIcon('heroicon-m-user-minus')
                ->color($expiringMembersCount > 0 ? 'warning' : 'gray')
                ->url('/admin/subscriptions'),

            Stat::make('Correction Notices Pending', $pendingNoticesCount)
                ->description('Material corrections needing parent notice')
                ->descriptionIcon('heroicon-m-bell-alert')
                ->color($pendingNoticesCount > 0 ? 'danger' : 'success')
                ->url('/admin/resource-corrections'),
        ];
    }
}
