<?php

namespace App\Filament\Resources\SubscriptionPlans;

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Services\ProductPrice;
use App\Domains\Membership\Models\SubscriptionPlan;
use App\Filament\Resources\SubscriptionPlans\Pages\CreateSubscriptionPlan;
use App\Filament\Resources\SubscriptionPlans\Pages\EditSubscriptionPlan;
use App\Filament\Resources\SubscriptionPlans\Pages\ListSubscriptionPlans;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

/**
 * Membership plans (docs/plan/phase-10-membership-programme.md, "Admin").
 * The amount a parent pays at checkout is the linked membership product's
 * price, not `subscription_plans.price` — the "Checkout price" column
 * flags a plan whose two prices have drifted apart so staff can fix the
 * product too. Nothing here changes a product's price automatically.
 */
class SubscriptionPlanResource extends Resource
{
    protected static ?string $model = SubscriptionPlan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static string|UnitEnum|null $navigationGroup = 'Membership';

    protected static ?string $navigationLabel = 'Plans';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Plan')
                    ->columns(2)
                    ->components([
                        TextInput::make('name')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->alphaDash()
                            ->disabledOn('edit')
                            ->helperText('A stable identifier such as pilot_90. It cannot change once created.'),
                        TextInput::make('currency')
                            ->default('INR')
                            ->maxLength(3)
                            ->required(),
                        TextInput::make('price')
                            ->label('Price (paise)')
                            ->integer()
                            ->minValue(0)
                            ->required()
                            ->helperText('₹499 is stored as 49900. Update the linked membership product\'s price too — that is what checkout charges.'),
                        TextInput::make('duration_days')
                            ->label('Duration (days)')
                            ->integer()
                            ->minValue(1)
                            ->required(),
                        TextInput::make('duration_months')
                            ->label('Duration (months)')
                            ->integer()
                            ->minValue(0),
                        Toggle::make('auto_renew_supported')
                            ->label('Auto-renew supported'),
                        Toggle::make('active')
                            ->default(true)
                            ->helperText('Switch off to stop selling this plan. Existing subscriptions are not affected.'),
                    ]),
                Section::make('Benefits')
                    ->components([
                        TextInput::make('benefits.member_discount_percent')
                            ->label('Member discount (%)')
                            ->integer()
                            ->minValue(0)
                            ->maxValue(100)
                            ->dehydrateStateUsing(fn (mixed $state): ?int => filled($state) ? (int) $state : null)
                            ->helperText('Applied to eligible one-time products while the membership is active.'),
                        Textarea::make('benefits.inclusions')
                            ->label('Inclusions')
                            ->helperText('Shown on the membership pages.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('price')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('code'),
                TextColumn::make('price')
                    ->formatStateUsing(fn (int $state): string => '₹'.number_format($state / 100, 2))
                    ->sortable(),
                TextColumn::make('checkout_price')
                    ->label('Checkout price')
                    ->state(fn (SubscriptionPlan $record): ?string => $record->products
                        ->map(fn (Product $product): string => '₹'.number_format(ProductPrice::for($product) / 100, 2))
                        ->implode(', ') ?: null)
                    ->color(fn (SubscriptionPlan $record): ?string => $record->products
                        ->contains(fn (Product $product): bool => ProductPrice::for($product) !== $record->price) ? 'danger' : null)
                    ->placeholder('No product'),
                TextColumn::make('duration_days')
                    ->label('Days'),
                IconColumn::make('auto_renew_supported')
                    ->label('Auto-renew')
                    ->boolean(),
                IconColumn::make('active')
                    ->boolean(),
            ])
            ->modifyQueryUsing(fn ($query) => $query->with('products'))
            ->recordActions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSubscriptionPlans::route('/'),
            'create' => CreateSubscriptionPlan::route('/create'),
            'edit' => EditSubscriptionPlan::route('/{record}/edit'),
        ];
    }
}
