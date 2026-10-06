<?php

namespace App\Filament\Resources\SchoolClasses\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SchoolClassForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('Set once. Changing it later is a deliberate action and does not happen automatically when the name changes.'),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('active')
                    ->required()
                    ->default(true),
                Textarea::make('intro')
                    ->columnSpanFull(),
                TextInput::make('seo_title'),
                TextInput::make('seo_description'),
            ]);
    }
}
