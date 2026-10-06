<?php

namespace App\Filament\Resources\SchoolClasses\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Manages `class_skill`: which skills belong to this class, each with its
 * own class-specific objective wording and difficulty. Creating or
 * deleting a skill itself belongs on SkillResource — this screen only
 * attaches, edits the mapping, and detaches.
 */
class SkillsRelationManager extends RelationManager
{
    protected static string $relationship = 'skills';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components($this->pivotFields());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('pivot_sort_order')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('pivot.difficulty_band')
                    ->label('Difficulty'),
                TextColumn::make('pivot.sort_order')
                    ->label('Sort order')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('pivot.active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->headerActions([
                AttachAction::make()
                    ->schema(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        ...$this->pivotFields(),
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }

    /**
     * @return array<int, Component>
     */
    private function pivotFields(): array
    {
        return [
            TextInput::make('learning_objective')
                ->columnSpanFull(),
            TextInput::make('difficulty_band'),
            TextInput::make('sort_order')
                ->numeric()
                ->default(0)
                ->required(),
            Toggle::make('active')
                ->default(true)
                ->required(),
        ];
    }
}
