<?php

namespace App\Filament\Pages\Analytics;

use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Learning\Enums\ProgressStatus;
use App\Domains\Learning\Models\ResourceProgress;
use App\Domains\Membership\Models\SubscriptionPlan;
use App\Models\User;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class PilotCohort extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|UnitEnum|null $navigationGroup = 'Analytics';

    protected static ?string $navigationLabel = 'Pilot cohort';

    protected static ?string $title = 'Pilot Cohort View (20–30 Families)';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.analytics.pilot-cohort';

    public static function canAccess(): bool
    {
        $user = Auth::user();

        if ($user === null) {
            return false;
        }

        if ($user->hasRole('super-admin') || $user->hasRole('business-admin')) {
            return true;
        }

        return $user->can('analytics.view');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->where(function (Builder $q) {
                        $q->whereHas('subscriptions')
                            ->orWhereHas('learningProfiles');
                    })
                    ->with(['subscriptions.plan', 'learningProfiles.progress', 'orders', 'supportNotes', 'feedback'])
            )
            ->columns([
                TextColumn::make('id')
                    ->label('Family Identifier')
                    ->formatStateUsing(function ($state, User $record) {
                        $parts = explode('@', $record->email);
                        $masked = substr($parts[0], 0, 1).'***@'.($parts[1] ?? 'domain');

                        return "Family #{$state} ({$masked})";
                    })
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Joined Date')
                    ->dateTime('M d, Y')
                    ->sortable(),

                TextColumn::make('weeks_active')
                    ->label('Weeks Active')
                    ->state(function (User $record): int {
                        $profileIds = $record->learningProfiles->pluck('id');
                        if ($profileIds->isEmpty()) {
                            return 0;
                        }

                        // Distinct calendar weeks with progress activity
                        return ResourceProgress::query()
                            ->whereIn('profile_id', $profileIds)
                            ->selectRaw('YEARWEEK(updated_at, 1) as yw')
                            ->distinct()
                            ->count('yw');
                    }),

                TextColumn::make('packs_opened')
                    ->label('Packs Opened')
                    ->state(function (User $record): int {
                        $profileIds = $record->learningProfiles->pluck('id');

                        return ResourceProgress::query()
                            ->whereIn('profile_id', $profileIds)
                            ->count();
                    }),

                TextColumn::make('packs_completed')
                    ->label('Packs Completed')
                    ->state(function (User $record): int {
                        $profileIds = $record->learningProfiles->pluck('id');

                        return ResourceProgress::query()
                            ->whereIn('profile_id', $profileIds)
                            ->where('status', ProgressStatus::Completed)
                            ->count();
                    }),

                TextColumn::make('total_purchases')
                    ->label('Total Purchases')
                    ->state(function (User $record): string {
                        $paise = $record->orders()
                            ->where('status', OrderStatus::Paid)
                            ->sum('total');

                        return '₹'.number_format($paise / 100, 2);
                    }),

                TextColumn::make('support_contacts')
                    ->label('Support Contacts')
                    ->state(fn (User $record): int => $record->supportNotes->count() + $record->feedback->count()),
            ])
            ->filters([
                SelectFilter::make('plan')
                    ->label('Subscription Plan')
                    ->options(fn (): array => SubscriptionPlan::query()->pluck('name', 'code')->all())
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        $data['value'],
                        fn (Builder $query, $planCode): Builder => $query->whereHas(
                            'subscriptions.plan',
                            fn (Builder $q) => $q->where('code', $planCode)
                        )
                    )),
            ])
            ->defaultSort('created_at', 'asc');
    }
}
