<?php

namespace App\Filament\Resources\Assessments;

use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Subject;
use App\Domains\Learning\Enums\AssessmentStatus;
use App\Domains\Learning\Models\Assessment;
use App\Filament\Resources\Assessments\Pages\CreateAssessment;
use App\Filament\Resources\Assessments\Pages\EditAssessment;
use App\Filament\Resources\Assessments\Pages\ListAssessments;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class AssessmentResource extends Resource
{
    protected static ?string $model = Assessment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|UnitEnum|null $navigationGroup = 'Learning & Content';

    protected static ?string $navigationLabel = 'Assessments';

    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('curriculum.view')
            || auth()->user()?->can('resources.view')
            || auth()->user()?->hasRole('super-admin') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('class_id')
                    ->label('School Class')
                    ->options(SchoolClass::query()->pluck('name', 'id'))
                    ->required(),
                Forms\Components\Select::make('subject_id')
                    ->label('Subject')
                    ->options(Subject::query()->pluck('name', 'id'))
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, Set $set) => $set('slug', Str::slug((string) $state))),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\Select::make('status')
                    ->options([
                        AssessmentStatus::Draft->value => 'Draft',
                        AssessmentStatus::InReview->value => 'In Review',
                        AssessmentStatus::Published->value => 'Published',
                        AssessmentStatus::Archived->value => 'Archived',
                    ])
                    ->default(AssessmentStatus::Draft->value)
                    ->required(),
                Forms\Components\TextInput::make('version')
                    ->numeric()
                    ->default(1)
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('schoolClass.name')
                    ->label('Class')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Subject')
                    ->sortable(),
                Tables\Columns\TextColumn::make('version')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (Assessment $record) => match ($record->status) {
                        AssessmentStatus::Published => 'success',
                        AssessmentStatus::InReview => 'warning',
                        AssessmentStatus::Draft => 'gray',
                        AssessmentStatus::Archived => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAssessments::route('/'),
            'create' => CreateAssessment::route('/create'),
            'edit' => EditAssessment::route('/{record}/edit'),
        ];
    }
}
