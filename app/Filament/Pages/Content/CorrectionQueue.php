<?php

namespace App\Filament\Pages\Content;

use App\Domains\Content\Models\ResourceVersion;
use App\Filament\Resources\LearningResources\LearningResourceResource;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

/**
 * Corrections in progress: a new, unpublished version on a resource that
 * already has a published one (step 4.9). The live version stays live
 * until the correction passes the same reviews and is published.
 */
class CorrectionQueue extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Correction queue';

    protected string $view = 'filament.pages.content.correction-queue';

    public static function canAccess(): bool
    {
        return (bool) Auth::user()?->can('resources.view');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ResourceVersion::query()
                    ->whereNull('published_at')
                    ->whereHas('resource', fn (Builder $query) => $query->whereNotNull('published_at')),
            )
            ->defaultSort('created_at')
            ->columns([
                TextColumn::make('resource.title')->label('Resource'),
                TextColumn::make('version')->label('New version'),
                TextColumn::make('correction_severity')->label('Severity')->badge(),
                TextColumn::make('customer_notice_required')->label('Notice required')->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No'),
                TextColumn::make('resource.status')->label('Status')->badge(),
                TextColumn::make('created_at')->label('Created')->dateTime(),
            ])
            ->recordActions([
                Action::make('manage')
                    ->label('Open')
                    ->url(fn (ResourceVersion $record): string => LearningResourceResource::getUrl('view', ['record' => $record->resource_id])),
            ]);
    }
}
