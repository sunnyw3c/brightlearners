<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Domains\Catalog\Models\Product;
use App\Support\Money;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product')
                    ->columns(3)
                    ->components([
                        TextEntry::make('name'),
                        TextEntry::make('type')->badge(),
                        TextEntry::make('status')->badge(),
                        TextEntry::make('primaryClass.name')->label('Class')->placeholder('All classes'),
                        TextEntry::make('regular_price')
                            ->label('Regular price')
                            ->formatStateUsing(fn (Product $record): string => Money::format($record->regular_price, $record->currency)),
                        TextEntry::make('sale_price')
                            ->label('Sale price')
                            ->placeholder('No active sale')
                            ->formatStateUsing(fn (Product $record): ?string => $record->sale_price !== null ? Money::format($record->sale_price, $record->currency) : null),
                        TextEntry::make('published_at')->dateTime()->placeholder('Not published'),
                        TextEntry::make('publish_at')->label('Scheduled for')->dateTime()->placeholder('Not scheduled'),
                        TextEntry::make('totalPages')
                            ->label('Total pages')
                            ->state(fn (Product $record): int => $record->totalPages()),
                    ]),
                Section::make('Included resources')
                    ->visible(fn (Product $record): bool => $record->resources->isNotEmpty())
                    ->components([
                        RepeatableEntry::make('resources')
                            ->label('')
                            ->columns(3)
                            ->components([
                                TextEntry::make('title'),
                                TextEntry::make('type')->badge(),
                                TextEntry::make('page_count')->label('Pages'),
                            ]),
                    ]),
                Section::make('Bundle children')
                    ->visible(fn (Product $record): bool => $record->childProducts->isNotEmpty())
                    ->components([
                        RepeatableEntry::make('childProducts')
                            ->label('')
                            ->columns(2)
                            ->components([
                                TextEntry::make('name'),
                                TextEntry::make('status')->badge(),
                            ]),
                    ]),
                Section::make('Cover')
                    ->visible(fn (Product $record): bool => filled($record->cover_path))
                    ->components([
                        ImageEntry::make('cover_path')->disk('previews')->label(''),
                    ]),
            ]);
    }
}
