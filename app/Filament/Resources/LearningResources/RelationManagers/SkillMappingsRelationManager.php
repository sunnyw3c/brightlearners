<?php

namespace App\Filament\Resources\LearningResources\RelationManagers;

use App\Domains\Content\Models\LearningResource;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Rules\SkillBelongsToClass;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Which (class, skill) pairs this resource is tagged to. The pair must
 * already be active in `class_skill` — enforced by `SkillBelongsToClass`
 * (Phase 3), not by a database constraint.
 *
 * @property-read LearningResource $ownerRecord
 */
class SkillMappingsRelationManager extends RelationManager
{
    protected static string $relationship = 'skillMappings';

    protected static ?string $title = 'Class and skill mapping';

    public function form(Schema $schema): Schema
    {
        return $schema->components($this->fields());
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('schoolClass.name')
                    ->label('Class'),
                TextColumn::make('skill.name')
                    ->label('Skill'),
                IconColumn::make('is_primary')
                    ->label('Primary')
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    /**
     * @return array<int, Component>
     */
    private function fields(): array
    {
        return [
            Select::make('class_id')
                ->label('Class')
                ->options(fn (): array => SchoolClass::query()->active()->orderBy('sort_order')->pluck('name', 'id')->all())
                ->live()
                ->required(),
            Select::make('skill_id')
                ->label('Skill')
                ->options(fn (Get $get): array => Skill::query()
                    ->active()
                    ->whereHas('classes', fn ($query) => $query->whereKey($get('class_id'))->where('class_skill.active', true))
                    ->pluck('name', 'id')
                    ->all())
                ->rules(fn (Get $get): array => [new SkillBelongsToClass($get('class_id'))])
                ->required(),
            Toggle::make('is_primary')
                ->label('Primary (decides the canonical URL)')
                ->default(fn (): bool => $this->ownerRecord->skillMappings()->doesntExist()),
        ];
    }
}
