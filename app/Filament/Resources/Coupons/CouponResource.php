<?php

namespace App\Filament\Resources\Coupons;

use App\Domains\Commerce\Enums\CouponType;
use App\Domains\Commerce\Models\Coupon;
use App\Filament\Resources\Coupons\Pages\CreateCoupon;
use App\Filament\Resources\Coupons\Pages\EditCoupon;
use App\Filament\Resources\Coupons\Pages\ListCoupons;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    protected static string|UnitEnum|null $navigationGroup = 'Commerce';

    protected static ?string $navigationLabel = 'Coupons';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'code';

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('coupons.manage') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(50)
                    ->extraInputAttributes(['style' => 'text-transform: uppercase']),
                Forms\Components\Select::make('type')
                    ->options([
                        CouponType::Percent->value => 'Percentage',
                        CouponType::Fixed->value => 'Fixed Amount (Paise)',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->numeric()
                    ->helperText('For percentage: enter 1 to 100. For fixed amount: enter paise (e.g. 5000 = ₹50).'),
                Forms\Components\TextInput::make('minimum_order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Minimum order amount in paise (e.g. 10000 = ₹100).'),
                Forms\Components\DateTimePicker::make('starts_at')
                    ->nullable(),
                Forms\Components\DateTimePicker::make('expires_at')
                    ->nullable(),
                Forms\Components\TextInput::make('max_uses')
                    ->numeric()
                    ->nullable()
                    ->helperText('Total maximum number of uses allowed across all users.'),
                Forms\Components\TextInput::make('uses_per_user')
                    ->numeric()
                    ->nullable()
                    ->helperText('Maximum number of uses allowed per user.'),
                Forms\Components\Toggle::make('active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->formatStateUsing(fn ($state) => is_object($state) ? $state->label() : ucfirst((string) $state)),
                Tables\Columns\TextColumn::make('value')
                    ->formatStateUsing(fn ($record) => $record->type === CouponType::Percent ? "{$record->value}%" : '₹'.number_format($record->value / 100, 2)),
                Tables\Columns\TextColumn::make('minimum_order')
                    ->formatStateUsing(fn ($state) => '₹'.number_format($state / 100, 2)),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime('M d, Y')
                    ->placeholder('Never'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCoupons::route('/'),
            'create' => CreateCoupon::route('/create'),
            'edit' => EditCoupon::route('/{record}/edit'),
        ];
    }
}
