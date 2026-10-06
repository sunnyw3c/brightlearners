<?php

namespace App\Filament\Resources\LearningResources\Tables;

use App\Domains\Content\Actions\SubmitResourceForReview;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Subject;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LearningResourcesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('skillMappings.schoolClass.name')
                    ->label('Class'),
                TextColumn::make('skillMappings.skill.topic.subject.name')
                    ->label('Subject'),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(ResourceStatus::class),
                SelectFilter::make('class')
                    ->options(fn (): array => SchoolClass::query()->active()->orderBy('sort_order')->pluck('name', 'id')->all())
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        $data['value'],
                        fn (Builder $query, $classId): Builder => $query->whereHas('skillMappings', fn (Builder $q) => $q->where('class_id', $classId)),
                    )),
                SelectFilter::make('subject')
                    ->options(fn (): array => Subject::query()->active()->orderBy('sort_order')->pluck('name', 'id')->all())
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        $data['value'],
                        fn (Builder $query, $subjectId): Builder => $query->whereHas(
                            'skillMappings.skill.topic',
                            fn (Builder $q) => $q->where('subject_id', $subjectId),
                        ),
                    )),
                SelectFilter::make('reviewer')
                    ->options(fn (): array => User::query()
                        ->whereHas('roles.permissions', fn (Builder $q) => $q->where('name', 'resources.review'))
                        ->pluck('name', 'id')
                        ->all())
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        $data['value'],
                        fn (Builder $query, $reviewerId): Builder => $query->whereHas(
                            'versions.reviews',
                            fn (Builder $q) => $q->where('reviewer_id', $reviewerId),
                        ),
                    )),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('submitForReview')
                    ->label('Submit for review')
                    ->icon('heroicon-o-paper-airplane')
                    ->requiresConfirmation()
                    ->visible(fn (LearningResource $record): bool => $record->status === ResourceStatus::Draft
                        && auth()->user()->can('update', $record))
                    ->action(fn (LearningResource $record) => app(SubmitResourceForReview::class)->handle($record)),
                Action::make('archive')
                    ->label('Archive')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (LearningResource $record): bool => $record->status === ResourceStatus::Published
                        && auth()->user()->can('publish', $record))
                    ->action(fn (LearningResource $record) => $record->update(['status' => ResourceStatus::Archived])),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
