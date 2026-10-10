<?php

namespace App\Filament\Resources\Redirects;

use App\Domains\Growth\Models\Redirect;
use App\Filament\Resources\Redirects\Pages\CreateRedirect;
use App\Filament\Resources\Redirects\Pages\EditRedirect;
use App\Filament\Resources\Redirects\Pages\ListRedirects;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
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

class RedirectResource extends Resource
{
    protected static ?string $model = Redirect::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    protected static string|UnitEnum|null $navigationGroup = 'Marketing';

    protected static ?string $navigationLabel = 'Redirects';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'from_path';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole(['super-admin', 'business-admin'])
            || (auth()->user()?->can('redirects.manage') ?? false);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('URL Redirect Rule')
                    ->description('Manages 301s and retired slugs. Refuses loops and redirect chains (step 12.10).')
                    ->columns(2)
                    ->components([
                        TextInput::make('from_path')
                            ->label('From Path')
                            ->placeholder('/old-page-slug')
                            ->required()
                            ->helperText('Relative path to redirect from. Must begin with a slash.'),
                        TextInput::make('to_path')
                            ->label('To Path / Destination')
                            ->placeholder('/new-page-slug')
                            ->required()
                            ->helperText('Destination path or full URL. Must not point back to source or another redirect.'),
                        Select::make('status_code')
                            ->options([
                                301 => '301 Permanent',
                                302 => '302 Temporary',
                            ])
                            ->default(301)
                            ->required(),
                        TextInput::make('hits')
                            ->disabled()
                            ->default(0)
                            ->numeric()
                            ->helperText('Incremented whenever this redirect is served upon a 404.'),
                        Toggle::make('active')
                            ->default(true)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('from_path')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('to_path')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status_code')
                    ->badge(),
                IconColumn::make('active')
                    ->boolean(),
                TextColumn::make('hits')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRedirects::route('/'),
            'create' => CreateRedirect::route('/create'),
            'edit' => EditRedirect::route('/{record}/edit'),
        ];
    }
}
