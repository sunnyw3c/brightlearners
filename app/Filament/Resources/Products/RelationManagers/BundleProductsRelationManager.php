<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Domains\Catalog\Enums\ProductType;
use App\Domains\Catalog\Models\Product;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * A bundle's child products (docs/plan/phase-06-product-catalogue.md,
 * step 6.3). Only shown for a product of type `bundle`. A bundle cannot
 * contain another bundle — the choices offered here already exclude
 * bundles, and `BundleProduct::booted()` refuses it again regardless.
 *
 * @property-read Product $ownerRecord
 */
class BundleProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'childProducts';

    protected static ?string $title = 'Bundle contents';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord instanceof Product && $ownerRecord->type === ProductType::Bundle;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('sort_order')
                ->numeric()
                ->default(0)
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('type')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('pivot.sort_order')->label('Sort order'),
            ])
            ->headerActions([
                AttachAction::make()
                    ->recordSelect(fn (Select $select) => $select->options(
                        fn (): array => Product::query()
                            ->where('type', '!=', ProductType::Bundle->value)
                            ->whereKeyNot($this->ownerRecord->id)
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->all(),
                    ))
                    ->schema(fn (Schema $schema) => $schema->components([
                        TextInput::make('sort_order')->numeric()->default(0)->required(),
                    ])),
            ])
            ->recordActions([
                EditAction::make(),
                DetachAction::make(),
            ]);
    }
}
