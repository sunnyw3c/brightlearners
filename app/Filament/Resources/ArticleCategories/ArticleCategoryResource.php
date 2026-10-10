<?php

namespace App\Filament\Resources\ArticleCategories;

use App\Domains\Growth\Models\ArticleCategory;
use App\Filament\Resources\ArticleCategories\Pages\CreateArticleCategory;
use App\Filament\Resources\ArticleCategories\Pages\EditArticleCategory;
use App\Filament\Resources\ArticleCategories\Pages\ListArticleCategories;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ArticleCategoryResource extends Resource
{
    protected static ?string $model = ArticleCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static string|UnitEnum|null $navigationGroup = 'Marketing';

    protected static ?string $navigationLabel = 'Categories';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole(['super-admin', 'business-admin'])
            || (auth()->user()?->can('articles.edit') ?? false);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category Information')
                    ->columns(2)
                    ->components([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Textarea::make('description')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('articles_count')
                    ->counts('articles')
                    ->label('Articles'),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListArticleCategories::route('/'),
            'create' => CreateArticleCategory::route('/create'),
            'edit' => EditArticleCategory::route('/{record}/edit'),
        ];
    }
}
