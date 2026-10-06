<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Domains\Catalog\Enums\ProductType;
use App\Domains\Curriculum\Models\SchoolClass;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

/**
 * Status is not editable here — a product only becomes `active` through
 * the "Activate" table action (`ActivateProduct`), so the deliverable
 * check in docs/plan/phase-06-product-catalogue.md (step 6.5) cannot be
 * bypassed by editing a field. The pricing section is hidden from anyone
 * without `products.edit-price`; `Product::booted()` enforces the same
 * rule again at the model layer.
 */
class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product')
                    ->columns(2)
                    ->components([
                        TextInput::make('name')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Stable once published. A later change needs a redirect (Phase 12).')
                            ->columnSpanFull(),
                        Select::make('type')
                            ->options(ProductType::class)
                            ->required()
                            ->live(),
                        Select::make('primary_class_id')
                            ->label('Primary class')
                            ->options(fn (): array => SchoolClass::query()->active()->orderBy('sort_order')->pluck('name', 'id')->all())
                            ->helperText('Leave blank for a flagship product with no single class (uses "all-classes" in its URL).'),
                        TextInput::make('sku')
                            ->label('SKU'),
                        TextInput::make('short_description')
                            ->maxLength(300)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->columnSpanFull(),
                        FileUpload::make('cover_path')
                            ->label('Cover image')
                            ->disk('previews')
                            ->directory('covers')
                            ->image()
                            ->columnSpanFull(),
                    ]),
                Section::make('Pricing')
                    ->columns(2)
                    ->visible(fn (): bool => (bool) Auth::user()?->can('products.edit-price'))
                    ->components([
                        TextInput::make('regular_price')
                            ->label('Regular price (paise)')
                            ->numeric()
                            ->required()
                            ->helperText('₹199 is stored as 19900.'),
                        TextInput::make('sale_price')
                            ->label('Sale price (paise)')
                            ->numeric()
                            ->helperText('Must be lower than the regular price.'),
                        DateTimePicker::make('sale_starts_at'),
                        DateTimePicker::make('sale_ends_at'),
                        TextInput::make('currency')
                            ->default('INR')
                            ->maxLength(3),
                        Toggle::make('member_discount_eligible')
                            ->label('Eligible for the member discount')
                            ->helperText('Affects price only (Phase 7). It never grants access.'),
                    ]),
                Section::make('Merchandising')
                    ->columns(2)
                    ->components([
                        Toggle::make('featured'),
                        DateTimePicker::make('publish_at')
                            ->label('Scheduled publish time')
                            ->helperText('The product activates automatically once its deliverable is ready.'),
                        TextInput::make('seo_title')
                            ->columnSpanFull(),
                        TextInput::make('seo_description')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
