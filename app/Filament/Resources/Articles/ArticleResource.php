<?php

namespace App\Filament\Resources\Articles;

use App\Domains\Catalog\Models\Product;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Growth\Enums\ArticleStatus;
use App\Domains\Growth\Models\Article;
use App\Filament\Resources\Articles\Pages\CreateArticle;
use App\Filament\Resources\Articles\Pages\EditArticle;
use App\Filament\Resources\Articles\Pages\ListArticles;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Marketing';

    protected static ?string $navigationLabel = 'Articles';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole(['super-admin', 'business-admin'])
            || (auth()->user()?->can('articles.edit') ?? false);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Article Content')
                    ->columns(2)
                    ->components([
                        TextInput::make('title')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Stable URL segment. Changing a published slug creates a 301 redirect automatically.')
                            ->columnSpanFull(),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('author_id')
                            ->relationship('author', 'name')
                            ->searchable()
                            ->preload()
                            ->default(fn () => auth()->id())
                            ->required(),
                        Select::make('status')
                            ->options(ArticleStatus::class)
                            ->default(ArticleStatus::Draft)
                            ->required(),
                        DateTimePicker::make('published_at')
                            ->helperText('Visible on the public site when status is published and date has arrived.'),
                        Textarea::make('excerpt')
                            ->rows(3)
                            ->columnSpanFull(),
                        RichEditor::make('body')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Related Content (Try this Block)')
                    ->description('Links to free resources, paid workbooks and curriculum classes rendered at the bottom of the article (step 12.14).')
                    ->components([
                        Repeater::make('relations')
                            ->relationship('relations')
                            ->schema([
                                Select::make('related_type')
                                    ->label('Type')
                                    ->options([
                                        LearningResource::class => 'Learning Resource / Worksheet',
                                        Product::class => 'Product / Workbook',
                                        SchoolClass::class => 'School Class',
                                        Skill::class => 'Skill',
                                    ])
                                    ->required(),
                                TextInput::make('related_id')
                                    ->label('ID')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->columns(3)
                            ->collapsible(),
                    ]),

                Section::make('SEO & Metadata')
                    ->description('Template-driven titles and descriptions can be overridden here (step 12.3).')
                    ->collapsed()
                    ->columns(2)
                    ->components([
                        TextInput::make('seo_title')
                            ->helperText('Leave empty to use automatic template: {title} | BrightLearners Parent Hub')
                            ->columnSpanFull(),
                        Textarea::make('seo_description')
                            ->helperText('Leave empty to use the article excerpt')
                            ->columnSpanFull(),
                        TextInput::make('og_image_path')
                            ->label('OG Image Path')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('category.name')
                    ->sortable(),
                TextColumn::make('author.name')
                    ->placeholder('None'),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('published_at')
                    ->dateTime('M d, Y')
                    ->placeholder('Unpublished')
                    ->sortable(),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListArticles::route('/'),
            'create' => CreateArticle::route('/create'),
            'edit' => EditArticle::route('/{record}/edit'),
        ];
    }
}
