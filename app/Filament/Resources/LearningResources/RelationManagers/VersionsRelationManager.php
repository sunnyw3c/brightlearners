<?php

namespace App\Filament\Resources\LearningResources\RelationManagers;

use App\Domains\Content\Actions\CreateResourceCorrection;
use App\Domains\Content\Actions\PublishResourceVersion;
use App\Domains\Content\Actions\RecordResourceReview;
use App\Domains\Content\Actions\ScheduleResourcePublish;
use App\Domains\Content\Actions\UploadResourceVersion;
use App\Domains\Content\Enums\CorrectionSeverity;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Enums\ReviewStatus;
use App\Domains\Content\Enums\ReviewType;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

/**
 * Upload, review and publish a resource's versions (step 4.3–4.9). Files
 * are handled by the domain actions, not by Filament's own file storage:
 * every FileUpload field below uses `storeFiles(false)` so the action
 * receives the real uploaded file and decides where it goes.
 *
 * @property-read LearningResource $ownerRecord
 */
class VersionsRelationManager extends RelationManager
{
    protected static string $relationship = 'versions';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('version'),
                TextColumn::make('preview_status')->badge(),
                IconColumn::make('is_current')->boolean(),
                TextColumn::make('reviewedBy.name')->label('Last reviewed by')->placeholder('—'),
                TextColumn::make('published_at')->dateTime()->placeholder('Not published'),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->headerActions([
                $this->uploadVersionAction(),
                $this->createCorrectionAction(),
            ])
            ->recordActions([
                $this->openFileAction(),
                $this->recordReviewAction(),
                $this->scheduleAction(),
                $this->publishAction(),
            ]);
    }

    private function uploadVersionAction(): Action
    {
        return Action::make('uploadVersion')
            ->label('Upload version')
            ->icon('heroicon-o-arrow-up-tray')
            ->visible(fn (): bool => $this->ownerRecord->versions()->doesntExist()
                && Auth::user()->can('update', $this->ownerRecord))
            ->schema([
                FileUpload::make('file')
                    ->label('PDF file')
                    ->storeFiles(false)
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize((int) config('content.max_upload_mb') * 1024)
                    ->required(),
                FileUpload::make('answer_file')
                    ->label('Answer key (optional)')
                    ->storeFiles(false)
                    ->acceptedFileTypes(['application/pdf']),
                FileUpload::make('low_ink_file')
                    ->label('Low-ink variant (optional)')
                    ->storeFiles(false)
                    ->acceptedFileTypes(['application/pdf']),
            ])
            ->action(function (array $data): void {
                app(UploadResourceVersion::class)->handle(
                    $this->ownerRecord,
                    $data['file'],
                    Auth::user(),
                    $data['answer_file'] ?? null,
                    $data['low_ink_file'] ?? null,
                );
            });
    }

    private function createCorrectionAction(): Action
    {
        return Action::make('createCorrection')
            ->label('Create correction')
            ->icon('heroicon-o-document-plus')
            ->visible(fn (): bool => $this->ownerRecord->currentVersion !== null
                && Auth::user()->can('update', $this->ownerRecord))
            ->schema([
                FileUpload::make('file')
                    ->label('Corrected PDF file')
                    ->storeFiles(false)
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize((int) config('content.max_upload_mb') * 1024)
                    ->required(),
                Select::make('severity')
                    ->options(CorrectionSeverity::class)
                    ->required(),
                Toggle::make('customer_notice_required')
                    ->label('Customers must be told'),
                Toggle::make('rewrite')
                    ->label('Full rewrite (bumps to the next whole version) instead of a correction'),
                Textarea::make('change_notes')
                    ->label('Change notes')
                    ->required()
                    ->columnSpanFull(),
            ])
            ->action(function (array $data): void {
                app(CreateResourceCorrection::class)->handle(
                    $this->ownerRecord,
                    $data['file'],
                    Auth::user(),
                    CorrectionSeverity::from($data['severity']),
                    (bool) $data['customer_notice_required'],
                    $data['change_notes'],
                    (bool) $data['rewrite'],
                );
            });
    }

    private function openFileAction(): Action
    {
        return Action::make('openFile')
            ->label('Open file')
            ->icon('heroicon-o-arrow-top-right-on-square')
            ->visible(fn (): bool => Auth::user()->can('resources.view'))
            ->url(fn (ResourceVersion $record): string => URL::temporarySignedRoute(
                'storage.resources',
                now()->addMinutes(5),
                ['path' => $record->file_path],
            ))
            ->openUrlInNewTab();
    }

    private function recordReviewAction(): Action
    {
        return Action::make('recordReview')
            ->label('Record review')
            ->icon('heroicon-o-clipboard-document-check')
            ->visible(fn (ResourceVersion $record): bool => in_array($record->resource->status, [
                ResourceStatus::EducationalReview,
                ResourceStatus::AnswerVerification,
                ResourceStatus::DesignPrintReview,
            ], true) && Auth::user()->can('review', $record->resource))
            ->schema([
                Select::make('review_type')
                    ->options(ReviewType::class)
                    ->required(),
                Select::make('status')
                    ->options([
                        ReviewStatus::Approved->value => 'Approved',
                        ReviewStatus::ChangesRequested->value => 'Changes requested',
                    ])
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ])
            ->action(function (ResourceVersion $record, array $data): void {
                app(RecordResourceReview::class)->handle(
                    $record,
                    ReviewType::from($data['review_type']),
                    Auth::user(),
                    ReviewStatus::from($data['status']),
                    $data['notes'] ?? null,
                );
            });
    }

    private function scheduleAction(): Action
    {
        return Action::make('schedule')
            ->label('Schedule')
            ->icon('heroicon-o-calendar')
            ->visible(fn (ResourceVersion $record): bool => $record->published_at === null
                && $record->resource->status === ResourceStatus::Approved
                && Auth::user()->can('publish', $record->resource))
            ->schema([
                DateTimePicker::make('publish_at')
                    ->label('Publish at')
                    ->minDate(now())
                    ->required(),
            ])
            ->action(function (ResourceVersion $record, array $data): void {
                app(ScheduleResourcePublish::class)->handle($record->resource, Carbon::parse($data['publish_at']));
            });
    }

    private function publishAction(): Action
    {
        return Action::make('publish')
            ->label('Publish')
            ->color('success')
            ->icon('heroicon-o-check-badge')
            ->requiresConfirmation()
            ->visible(fn (ResourceVersion $record): bool => $record->published_at === null
                && in_array($record->resource->status, [ResourceStatus::Approved, ResourceStatus::Scheduled], true)
                && Auth::user()->can('publish', $record->resource))
            ->action(fn (ResourceVersion $record) => app(PublishResourceVersion::class)->handle($record));
    }
}
