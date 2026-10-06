<?php

namespace App\Filament\Resources\LearningResources\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

/**
 * The staff preview screen (step 4.11): metadata, every preview image
 * and the review trail, for the most recently uploaded version — draft
 * or published. Links that open the private source files are on the
 * Versions tab, since they need a version row to act on.
 */
class LearningResourceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Metadata')
                    ->columns(3)
                    ->components([
                        TextEntry::make('title'),
                        TextEntry::make('type')->badge(),
                        TextEntry::make('status')->badge(),
                        TextEntry::make('learning_objective')->label('Learning objective')->columnSpanFull(),
                        TextEntry::make('difficulty'),
                        TextEntry::make('estimated_minutes')->label('Estimated minutes'),
                        TextEntry::make('page_count')->label('Page count'),
                        TextEntry::make('published_at')->dateTime()->placeholder('Not published'),
                        TextEntry::make('scheduled_for')->dateTime()->placeholder('Not scheduled'),
                    ]),
                Section::make('Latest version')
                    ->columns(3)
                    ->visible(fn ($record): bool => $record->latestVersion !== null)
                    ->components([
                        TextEntry::make('latestVersion.version')->label('Version'),
                        TextEntry::make('latestVersion.preview_status')->label('Preview status')->badge(),
                        TextEntry::make('latestVersion.checksum')->label('Checksum')->limit(16),
                        TextEntry::make('latestVersion.creator.name')->label('Uploaded by'),
                        TextEntry::make('latestVersion.reviewed_by')->label('Last reviewed by')->placeholder('—'),
                        TextEntry::make('latestVersion.reviewed_at')->label('Last reviewed at')->dateTime()->placeholder('—'),
                    ]),
                Section::make('Previews')
                    ->visible(fn ($record): bool => $record->latestVersion?->previews->isNotEmpty())
                    ->components([
                        RepeatableEntry::make('latestVersion.previews')
                            ->label('')
                            ->columns(3)
                            ->components([
                                ImageEntry::make('image_path')->disk('previews')->label(''),
                                TextEntry::make('page_no')->label('Page'),
                            ]),
                    ]),
                Section::make('Review trail')
                    ->visible(fn ($record): bool => $record->latestVersion?->reviews->isNotEmpty())
                    ->components([
                        RepeatableEntry::make('latestVersion.reviews')
                            ->label('')
                            ->columns(4)
                            ->components([
                                TextEntry::make('review_type')->label('Type')->badge(),
                                TextEntry::make('status')->badge(),
                                TextEntry::make('reviewer.name')->label('Reviewer'),
                                TextEntry::make('reviewed_at')->label('Reviewed at')->dateTime(),
                                TextEntry::make('notes')->columnSpanFull()->placeholder('—'),
                            ]),
                    ]),
            ]);
    }
}
