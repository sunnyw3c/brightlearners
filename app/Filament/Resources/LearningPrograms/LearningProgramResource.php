<?php

namespace App\Filament\Resources\LearningPrograms;

use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Membership\Enums\LearningProgramStatus;
use App\Domains\Membership\Models\LearningProgram;
use App\Filament\Resources\LearningPrograms\Pages\CreateLearningProgram;
use App\Filament\Resources\LearningPrograms\Pages\EditLearningProgram;
use App\Filament\Resources\LearningPrograms\Pages\ListLearningPrograms;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class LearningProgramResource extends Resource
{
    protected static ?string $model = LearningProgram::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static string|UnitEnum|null $navigationGroup = 'Membership';

    protected static ?string $navigationLabel = 'Programmes';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('curriculum.manage') || auth()->user()?->hasRole('super-admin') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('class_id')
                    ->label('School Class')
                    ->options(SchoolClass::query()->pluck('name', 'id'))
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('month')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(12)
                    ->required(),
                Forms\Components\TextInput::make('year')
                    ->numeric()
                    ->default((int) date('Y'))
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        LearningProgramStatus::Draft->value => 'Draft',
                        LearningProgramStatus::Ready->value => 'Ready',
                        LearningProgramStatus::Published->value => 'Published',
                    ])
                    ->required(),
                Forms\Components\DateTimePicker::make('publish_at')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('schoolClass.name')
                    ->label('Class')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('month')
                    ->label('Month/Year')
                    ->formatStateUsing(fn (LearningProgram $record) => date('F', mktime(0, 0, 0, $record->month, 10)).' '.$record->year),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (LearningProgram $record) => match ($record->status) {
                        LearningProgramStatus::Published => 'success',
                        LearningProgramStatus::Ready => 'info',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('weeks_count')
                    ->counts('weeks')
                    ->label('Weeks (1–4)'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLearningPrograms::route('/'),
            'create' => CreateLearningProgram::route('/create'),
            'edit' => EditLearningProgram::route('/{record}/edit'),
        ];
    }
}
