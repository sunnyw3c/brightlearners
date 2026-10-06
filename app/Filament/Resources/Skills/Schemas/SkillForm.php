<?php

namespace App\Filament\Resources\Skills\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Unique;

class SkillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('topic_id')
                    ->relationship('topic', 'name')
                    ->required()
                    ->live(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required()
                    ->unique(
                        ignoreRecord: true,
                        modifyRuleUsing: fn (Unique $rule, $get) => $rule->where('topic_id', $get('topic_id')),
                    )
                    ->helperText('Set once, unique within the topic. Changing it later is a deliberate action.'),
                Textarea::make('learning_objective')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('difficulty_band'),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('active')
                    ->required()
                    ->default(true),
            ]);
    }
}
