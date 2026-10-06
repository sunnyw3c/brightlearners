<?php

namespace App\Filament\Resources\LearningResources\Schemas;

use App\Domains\Content\Enums\ResourceType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

/**
 * Status, scheduling and publishing are not editable here — they only
 * change through the review and publish actions on the Versions tab, so
 * the workflow in docs/plan/phase-04-resource-engine.md (step 4.6) cannot
 * be bypassed by editing a field.
 */
class LearningResourceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Resource')
                    ->columns(2)
                    ->components([
                        TextInput::make('title')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Stable once published. A later change needs a redirect (Phase 5/12).')
                            ->columnSpanFull(),
                        Select::make('type')
                            ->options(ResourceType::class)
                            ->required(),
                        TextInput::make('difficulty'),
                        TextInput::make('estimated_minutes')
                            ->label('Estimated minutes')
                            ->numeric(),
                        TextInput::make('page_count')
                            ->label('Page count')
                            ->numeric()
                            ->helperText('Read from the PDF automatically after upload. Correct by hand if needed.'),
                        TextInput::make('summary')
                            ->maxLength(300)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->columnSpanFull(),
                        Textarea::make('learning_objective')
                            ->label('Learning objective')
                            ->helperText('Required before this resource can be submitted for review.')
                            ->columnSpanFull(),
                        Textarea::make('supplies')
                            ->columnSpanFull(),
                        TextInput::make('language')
                            ->default('en'),
                        TextInput::make('licence_type')
                            ->label('Licence type')
                            ->default('household'),
                    ]),
                Section::make('Flags')
                    ->columns(3)
                    ->components([
                        Toggle::make('has_answer_key')
                            ->label('Has answer key'),
                        Toggle::make('low_ink_available')
                            ->label('Low-ink variant available'),
                        Toggle::make('is_free')
                            ->label('Free resource'),
                        Toggle::make('featured'),
                        Toggle::make('ai_assisted')
                            ->label('AI-assisted')
                            ->helperText('Staff only. Never shown publicly. The same review gates still apply.'),
                    ]),
            ]);
    }
}
