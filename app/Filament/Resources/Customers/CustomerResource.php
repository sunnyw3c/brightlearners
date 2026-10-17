<?php

namespace App\Filament\Resources\Customers;

use App\Models\User;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class CustomerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|UnitEnum|null $navigationGroup = 'Customers';

    protected static ?string $navigationLabel = 'Parents';

    protected static ?string $modelLabel = 'customer';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        if ($user === null) {
            return false;
        }

        return $user->can('customers.view')
            || $user->can('customers.view-limited')
            || $user->hasRole('super-admin')
            || $user->hasRole('business-admin')
            || $user->hasRole('customer-support');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereDoesntHave('roles')
            ->withCount(['learningProfiles', 'orders']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email_verified_at')
                    ->label('Verified')
                    ->dateTime('M j, Y')
                    ->placeholder('Unverified')
                    ->sortable(),
                TextColumn::make('learning_profiles_count')
                    ->label('Children')
                    ->counts('learningProfiles')
                    ->badge(),
                TextColumn::make('orders_count')
                    ->label('Orders')
                    ->counts('orders')
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                ViewAction::make()->label('Support View'),
            ])
            ->bulkActions([]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'view' => Pages\ViewCustomer::route('/{record}'),
        ];
    }
}
