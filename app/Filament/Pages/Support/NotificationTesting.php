<?php

namespace App\Filament\Pages\Support;

use App\Domains\Commerce\Models\Order;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceCorrection;
use App\Domains\Membership\Models\LearningProgram;
use App\Domains\Membership\Models\LearningWeek;
use App\Domains\Membership\Models\Subscription;
use App\Notifications\CorrectionNotice;
use App\Notifications\MembershipExpiringNotice;
use App\Notifications\MonthlyCheckAvailable;
use App\Notifications\OrderConfirmation;
use App\Notifications\WeeklyPackReleased;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class NotificationTesting extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBellAlert;

    protected static string|UnitEnum|null $navigationGroup = 'Support & Operations';

    protected static ?string $navigationLabel = 'Notification previews';

    protected static ?string $title = 'Notification Previews & Test Sends';

    protected string $view = 'filament.pages.support.notification-testing';

    public static function canAccess(): bool
    {
        return (bool) Auth::user()?->isStaff();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('testOrderConfirmation')
                ->label('Send Test Order Confirmation')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {
                    $user = Auth::user();
                    $order = Order::latest()->first() ?? Order::factory()->create(['user_id' => $user->id]);
                    $user->notify(new OrderConfirmation($order));

                    FilamentNotification::make()
                        ->title('Order Confirmation test queued to '.$user->email)
                        ->success()
                        ->send();
                }),

            Action::make('testCorrectionNotice')
                ->label('Send Test Correction Notice')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function () {
                    $user = Auth::user();
                    $resource = LearningResource::first() ?? LearningResource::factory()->published()->create();
                    $correction = ResourceCorrection::latest()->first() ?? ResourceCorrection::factory()->create([
                        'resource_id' => $resource->id,
                        'version_from' => '1.0',
                        'version_to' => '1.1',
                        'notes' => 'Fixed typo on problem 4.',
                    ]);
                    $user->notify(new CorrectionNotice($correction));

                    FilamentNotification::make()
                        ->title('Correction Notice test queued to '.$user->email)
                        ->success()
                        ->send();
                }),

            Action::make('testMembershipExpiring')
                ->label('Send Test Expiry Notice')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    $user = Auth::user();
                    $sub = Subscription::latest()->first() ?? Subscription::factory()->active()->create(['user_id' => $user->id]);
                    $user->notify(new MembershipExpiringNotice($sub, 7));

                    FilamentNotification::make()
                        ->title('Membership Expiry Notice test queued to '.$user->email)
                        ->success()
                        ->send();
                }),

            Action::make('testWeeklyPack')
                ->label('Send Test Weekly Pack')
                ->color('info')
                ->requiresConfirmation()
                ->action(function () {
                    $user = Auth::user();
                    $week = LearningWeek::latest()->first() ?? LearningWeek::factory()->released()->create();
                    $user->notify(new WeeklyPackReleased($week));

                    FilamentNotification::make()
                        ->title('Weekly Pack Released test queued to '.$user->email)
                        ->success()
                        ->send();
                }),

            Action::make('testMonthlyCheck')
                ->label('Send Test Monthly Programme')
                ->color('gray')
                ->requiresConfirmation()
                ->action(function () {
                    $user = Auth::user();
                    $program = LearningProgram::latest()->first() ?? LearningProgram::factory()->published()->create();
                    $user->notify(new MonthlyCheckAvailable($program));

                    FilamentNotification::make()
                        ->title('Monthly Check Available test queued to '.$user->email)
                        ->success()
                        ->send();
                }),
        ];
    }
}
