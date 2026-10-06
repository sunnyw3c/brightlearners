<?php

namespace App\Filament\Pages\Content;

use App\Domains\Content\Enums\ResourceStatus;
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
 * Every version whose resource is waiting on an educational, answer or
 * design/print review (step 4.6; the admin section of
 * docs/plan/phase-04-resource-engine.md).
 */
class ReviewQueue extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Content review';

    protected string $view = 'filament.pages.content.review-queue';

    public static function canAccess(): bool
    {
        return (bool) Auth::user()?->can('resources.review');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ResourceVersion::query()
                    ->whereNull('published_at')
                    ->whereHas('resource', fn (Builder $query) => $query->whereIn('status', [
                        ResourceStatus::EducationalReview,
                        ResourceStatus::AnswerVerification,
                        ResourceStatus::DesignPrintReview,
                    ])),
            )
            ->defaultSort('created_at')
            ->columns([
                TextColumn::make('resource.title')->label('Resource'),
                TextColumn::make('version'),
                TextColumn::make('resource.status')->label('Waiting for')->badge(),
                TextColumn::make('creator.name')->label('Uploaded by'),
                TextColumn::make('created_at')->label('Uploaded')->dateTime(),
            ])
            ->recordActions([
                Action::make('manage')
                    ->label('Open')
                    ->url(fn (ResourceVersion $record): string => LearningResourceResource::getUrl('view', ['record' => $record->resource_id])),
            ]);
    }
}
