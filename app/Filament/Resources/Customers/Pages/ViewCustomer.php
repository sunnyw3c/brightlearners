<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Domains\Access\Actions\GrantManualEntitlement;
use App\Domains\Access\Actions\RevokeManualEntitlement;
use App\Domains\Access\Models\Entitlement;
use App\Domains\Access\Services\AccessService;
use App\Domains\Accounts\Models\SupportNote;
use App\Domains\Content\Models\LearningResource;
use App\Filament\Resources\Customers\CustomerResource;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewCustomer extends ViewRecord
{
    protected static string $resource = CustomerResource::class;

    protected string $view = 'filament.resources.customers.view-customer';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('checkAccess')
                ->label('Check Access (Why?)')
                ->icon('heroicon-o-question-mark-circle')
                ->color('gray')
                ->form([
                    Select::make('resource_id')
                        ->label('Learning Resource')
                        ->options(LearningResource::query()->pluck('title', 'id'))
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data, AccessService $accessService): void {
                    $resource = LearningResource::find($data['resource_id']);
                    if (! $resource) {
                        return;
                    }

                    $decision = $accessService->canAccess($this->record, $resource);
                    $source = $decision->source ? " (Source: {$decision->source})" : '';

                    Notification::make()
                        ->title($decision->allowed ? 'Access ALLOWED' : 'Access DENIED')
                        ->body("Reason: {$decision->reason->value}{$source}")
                        ->status($decision->allowed ? 'success' : 'danger')
                        ->persistent()
                        ->send();
                }),

            Action::make('grantAccess')
                ->label('Grant Manual Access')
                ->icon('heroicon-o-key')
                ->color('primary')
                ->form([
                    Select::make('resource_id')
                        ->label('Learning Resource')
                        ->options(LearningResource::query()->pluck('title', 'id'))
                        ->searchable()
                        ->required(),
                    Textarea::make('reason')
                        ->label('Mandatory Reason for Grant')
                        ->helperText('State the support ticket or customer communication context.')
                        ->required(),
                    DateTimePicker::make('ends_at')
                        ->label('Expiry Date (Optional)')
                        ->nullable(),
                ])
                ->action(function (array $data, GrantManualEntitlement $grantAction): void {
                    $resource = LearningResource::findOrFail($data['resource_id']);
                    $endsAt = ! empty($data['ends_at']) ? Carbon::parse($data['ends_at']) : null;

                    $grantAction->handle($this->record, $resource, $data['reason'], $endsAt);

                    Notification::make()
                        ->title('Access Granted')
                        ->body("Manual entitlement created for '{$resource->title}'.")
                        ->success()
                        ->send();
                }),

            Action::make('addNote')
                ->label('Add Support Note')
                ->icon('heroicon-o-chat-bubble-bottom-center-text')
                ->color('gray')
                ->form([
                    Textarea::make('note')
                        ->label('Internal Note')
                        ->required(),
                ])
                ->action(function (array $data): void {
                    SupportNote::query()->create([
                        'user_id' => $this->record->id,
                        'author_id' => auth()->id() ?? 1,
                        'note' => $data['note'],
                    ]);

                    Notification::make()
                        ->title('Note Added')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function revokeEntitlement(int $entitlementId, string $reason): void
    {
        $entitlement = Entitlement::findOrFail($entitlementId);

        app(RevokeManualEntitlement::class)->handle($entitlement, $reason);

        Notification::make()
            ->title('Access Revoked')
            ->success()
            ->send();
    }
}
