<?php

namespace App\Filament\Resources\LearningResources;

use App\Domains\Content\Models\LearningResource;
use App\Filament\Resources\LearningResources\Pages\CreateLearningResource;
use App\Filament\Resources\LearningResources\Pages\EditLearningResource;
use App\Filament\Resources\LearningResources\Pages\ListLearningResources;
use App\Filament\Resources\LearningResources\Pages\ViewLearningResource;
use App\Filament\Resources\LearningResources\RelationManagers\SkillMappingsRelationManager;
use App\Filament\Resources\LearningResources\RelationManagers\VersionsRelationManager;
use App\Filament\Resources\LearningResources\Schemas\LearningResourceForm;
use App\Filament\Resources\LearningResources\Schemas\LearningResourceInfolist;
use App\Filament\Resources\LearningResources\Tables\LearningResourcesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class LearningResourceResource extends Resource
{
    protected static ?string $model = LearningResource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Resources';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return LearningResourceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LearningResourceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LearningResourcesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            SkillMappingsRelationManager::class,
            VersionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLearningResources::route('/'),
            'create' => CreateLearningResource::route('/create'),
            'view' => ViewLearningResource::route('/{record}'),
            'edit' => EditLearningResource::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
