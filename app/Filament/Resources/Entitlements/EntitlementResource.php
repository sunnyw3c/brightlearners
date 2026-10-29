<?php

namespace App\Filament\Resources\Entitlements;

use App\Domains\Access\Actions\GrantManualEntitlement;
use App\Domains\Access\Actions\RevokeManualEntitlement;
use App\Domains\Access\Models\Entitlement;
use App\Domains\Access\Services\AccessService;
use App\Domains\Content\Models\LearningResource;
use App\Filament\Resources\Entitlements\Pages\ListEntitlements;
use App\Models\User;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class EntitlementResource extends Resource
{
    protected static ?string $model = Entitlement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static string|UnitEnum|null $navigationGroup = 'Support & Operations';

    protected static ?string $navigationLabel = 'Entitlements';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('entitlements.override') || auth()->user()?->can('customers.view') || auth()->user()?->hasRole('super-admin') ?? false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('resource.title')
                    ->label('Resource')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('source_type')
                    ->badge(),
                Tables\Columns\TextColumn::make('starts_at')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->dateTime('M d, Y H:i')
                    ->placeholder('Never'),
                Tables\Columns\TextColumn::make('revoked_at')
                    ->label('Status')
                    ->formatStateUsing(fn (Entitlement $record) => $record->isValid() ? 'Active' : ($record->revoked_at ? 'Revoked' : 'Expired'))
                    ->badge()
                    ->color(fn (Entitlement $record) => $record->isValid() ? 'success' : 'danger'),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                Action::make('grant')
                    ->label('Grant Admin Access')
                    ->icon('heroicon-o-plus')
                    ->form([
                        Forms\Components\Select::make('user_id')
                            ->label('Customer User')
                            ->options(User::query()->pluck('email', 'id'))
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('resource_id')
                            ->label('Learning Resource')
                            ->options(LearningResource::query()->pluck('title', 'id'))
                            ->searchable()
                            ->required(),
                        Forms\Components\DateTimePicker::make('ends_at')
                            ->label('Expiry Date (Optional)')
                            ->nullable(),
                        Forms\Components\Textarea::make('reason')
                            ->label('Reason for Grant')
                            ->required(),
                    ])
                    ->action(function (array $data): void {
                        $user = User::findOrFail($data['user_id']);
                        $resource = LearningResource::findOrFail($data['resource_id']);
                        $endsAt = ! empty($data['ends_at']) ? Carbon::parse($data['ends_at']) : null;

                        app(GrantManualEntitlement::class)->handle(
                            $user,
                            $resource,
                            $data['reason'],
                            $endsAt
                        );

                        Notification::make()
                            ->title('Entitlement Granted')
                            ->success()
                            ->send();
                    }),
                Action::make('checkAccess')
                    ->label('Check Access (Why?)')
                    ->icon('heroicon-o-question-mark-circle')
                    ->color('gray')
                    ->form([
                        Forms\Components\Select::make('user_id')
                            ->label('Customer User')
                            ->options(User::query()->pluck('email', 'id'))
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('resource_id')
                            ->label('Learning Resource')
                            ->options(LearningResource::query()->pluck('title', 'id'))
                            ->searchable()
                            ->required(),
                    ])
                    ->action(function (array $data): void {
                        $user = User::find($data['user_id']);
                        $resource = LearningResource::find($data['resource_id']);

                        if (! $user || ! $resource) {
                            return;
                        }

                        $decision = app(AccessService::class)->canAccess($user, $resource);

                        $sourceText = $decision->source ? " (Source: {$decision->source})" : '';
                        $reasonText = $decision->reason->value;

                        Notification::make()
                            ->title($decision->allowed ? 'Access ALLOWED' : 'Access DENIED')
                            ->body("Result: {$reasonText}{$sourceText}")
                            ->status($decision->allowed ? 'success' : 'danger')
                            ->persistent()
                            ->send();
                    }),
            ])
            ->actions([
                Action::make('revoke')
                    ->label('Revoke')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Entitlement $record) => $record->revoked_at === null)
                    ->form([
                        Forms\Components\Textarea::make('reason')
                            ->label('Revocation Reason')
                            ->required(),
                    ])
                    ->action(function (Entitlement $record, array $data): void {
                        app(RevokeManualEntitlement::class)->handle(
                            $record,
                            $data['reason']
                        );

                        Notification::make()
                            ->title('Entitlement Revoked')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEntitlements::route('/'),
        ];
    }
}
