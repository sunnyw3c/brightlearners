<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;

/**
 * There is no delete button for curriculum rows. Archiving (`active =
 * false`) is the only retirement path from the admin UI; the policy's
 * `delete` ability still exists as a server-side safety net, but nothing
 * in the UI calls it.
 */
class ArchiveAction
{
    public static function make(): Action
    {
        return Action::make('archive')
            ->label('Archive')
            ->color('danger')
            ->requiresConfirmation()
            ->visible(fn (Model $record): bool => (bool) $record->getAttribute('active'))
            ->action(fn (Model $record) => $record->update(['active' => false]));
    }
}
