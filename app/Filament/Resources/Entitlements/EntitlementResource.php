<?php

namespace App\Filament\Resources\Entitlements;

use App\Domains\Access\Models\Entitlement;
use App\Domains\Content\Models\LearningResource;
use App\Filament\Resources\Entitlements\Pages\ListEntitlements;
use App\Models\User;
use BackedEnum;
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

    protected static string|UnitEnum|null $navigationGroup = 'Access & Customers';

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
                Tables\Actions\Action::make('grant')
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
                        Entitlement::query()->create([
                            'user_id' => $data['user_id'],
                            'resource_id' => $data['resource_id'],
                            'source_type' => 'admin_grant',
                            'starts_at' => now(),
                            'ends_at' => $data['ends_at'] ?? null,
                            'metadata' => [
                                'reason' => $data['reason'],
                                'granted_by' => auth()->id(),
                            ],
                        ]);

                        Notification::make()
                            ->title('Entitlement Granted')
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('revoke')
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
                        $metadata = $record->metadata ?? [];
                        $metadata['revocation_reason'] = $data['reason'];
                        $metadata['revoked_by'] = auth()->id();

                        $record->update([
                            'revoked_at' => now(),
                            'metadata' => $metadata,
                        ]);

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
