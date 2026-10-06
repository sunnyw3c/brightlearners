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
 * Versions that have waited for a review longer than
 * config('content.overdue_review_days') — the operational-bottleneck
 * control in docs/plan/phase-04-resource-engine.md ("Risks and controls").
 */
class OverdueReviews extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Overdue reviews';

    protected string $view = 'filament.pages.content.overdue-reviews';

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
                    ->where('created_at', '<=', now()->subDays((int) config('content.overdue_review_days')))
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
                TextColumn::make('created_at')->label('Uploaded')->dateTime()->since(),
            ])
            ->recordActions([
                Action::make('manage')
                    ->label('Open')
                    ->url(fn (ResourceVersion $record): string => LearningResourceResource::getUrl('view', ['record' => $record->resource_id])),
            ]);
    }
}
