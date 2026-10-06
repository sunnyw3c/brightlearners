<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Domains\Catalog\Enums\ProductType;
use App\Domains\Catalog\Models\Product;
use App\Domains\Content\Models\LearningResource;
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
 * Which resources this product includes, with a sort order
 * (docs/plan/phase-06-product-catalogue.md, step 6.2). Not shown for a
 * bundle, which points at other products instead
 * (`BundleProductsRelationManager`).
 *
 * @property-read Product $ownerRecord
 */
class ProductResourcesRelationManager extends RelationManager
{
    protected static string $relationship = 'resources';

    protected static ?string $title = 'Included resources';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return ! $ownerRecord instanceof Product || $ownerRecord->type !== ProductType::Bundle;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('sort_order')
                ->numeric()
                ->default(0)
                ->required(),
            Select::make('version_policy')
                ->options(['current' => 'Always the current version'])
                ->default('current')
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('type')->badge(),
                TextColumn::make('page_count')->label('Pages'),
                TextColumn::make('pivot.sort_order')->label('Sort order'),
            ])
            ->headerActions([
                AttachAction::make()
                    ->recordSelect(fn (Select $select) => $select->options(
                        fn (): array => LearningResource::query()->orderBy('title')->pluck('title', 'id')->all(),
                    ))
                    ->schema(fn (Schema $schema) => $schema->components([
                        TextInput::make('sort_order')->numeric()->default(0)->required(),
                        Select::make('version_policy')
                            ->options(['current' => 'Always the current version'])
                            ->default('current')
                            ->required(),
                    ])),
            ])
            ->recordActions([
                EditAction::make(),
                DetachAction::make(),
            ]);
    }
}
