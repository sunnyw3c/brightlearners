<?php

namespace App\Filament\Resources\Feedback;

use App\Domains\Accounts\Enums\FeedbackCategory;
use App\Domains\Accounts\Models\Feedback;
use App\Filament\Resources\Feedback\Pages\ListFeedback;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class FeedbackResource extends Resource
{
    protected static ?string $model = Feedback::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleBottomCenterText;

    protected static string|UnitEnum|null $navigationGroup = 'Customers';

    protected static ?string $navigationLabel = 'Parent Feedback';

    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        if ($user === null) {
            return false;
        }

        if ($user->hasRole('super-admin') || $user->hasRole('business-admin')) {
            return true;
        }

        return $user->can('users.view') || $user->can('analytics.view');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
                TextColumn::make('category')
                    ->badge()
                    ->color(fn (FeedbackCategory $state): string => match ($state) {
                        FeedbackCategory::Content => 'info',
                        FeedbackCategory::Ux => 'warning',
                        FeedbackCategory::Payment => 'danger',
                        FeedbackCategory::Support => 'success',
                    }),
                TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($state) => $state ? "{$state} / 5" : '—')
                    ->sortable(),
                TextColumn::make('message')
                    ->label('Message')
                    ->limit(60)
                    ->searchable()
                    ->tooltip(fn (Feedback $record): string => $record->message),
                TextColumn::make('user.email')
                    ->label('Parent Email')
                    ->searchable()
                    ->placeholder('Anonymous / Guest'),
                TextColumn::make('learningResource.title')
                    ->label('Resource')
                    ->searchable()
                    ->placeholder('General Site'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'gray',
                        'reviewed' => 'info',
                        'resolved' => 'success',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options(FeedbackCategory::class),
                SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'reviewed' => 'Reviewed',
                        'resolved' => 'Resolved',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('markReviewed')
                    ->label('Mark Reviewed')
                    ->icon('heroicon-o-check')
                    ->color('info')
                    ->visible(fn (Feedback $record): bool => $record->status === 'new')
                    ->action(function (Feedback $record): void {
                        $record->update(['status' => 'reviewed']);
                        Notification::make()->title('Feedback marked as reviewed')->success()->send();
                    }),
                Action::make('markResolved')
                    ->label('Resolve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Feedback $record): bool => $record->status !== 'resolved')
                    ->action(function (Feedback $record): void {
                        $record->update(['status' => 'resolved']);
                        Notification::make()->title('Feedback marked as resolved')->success()->send();
                    }),
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFeedback::route('/'),
        ];
    }
}
