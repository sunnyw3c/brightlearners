<?php

namespace App\Filament\Pages\Support;

use App\Domains\Learning\Models\ResourceProgress;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class ProgressTroubleshooting extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static string|UnitEnum|null $navigationGroup = 'Support';

    protected static ?string $navigationLabel = 'Progress troubleshooting';

    protected static ?string $title = 'Learning Progress Summary (Support)';

    protected string $view = 'filament.pages.support.progress-troubleshooting';

    public static function canAccess(): bool
    {
        $user = Auth::user();

        if ($user === null) {
            return false;
        }

        if ($user->hasRole('super-admin') || $user->hasRole('customer-support')) {
            return true;
        }

        return $user->can('customers.view-limited') || $user->can('customers.view');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ResourceProgress::query()
                    ->with(['learningProfile.schoolClass', 'resource', 'user'])
                    ->latest('updated_at')
            )
            ->columns([
                TextColumn::make('user.email')
                    ->label('Parent Account')
                    ->searchable(),
                TextColumn::make('learningProfile.nickname')
                    ->label('Child Nickname')
                    ->searchable(),
                TextColumn::make('learningProfile.schoolClass.name')
                    ->label('Class'),
                TextColumn::make('resource.title')
                    ->label('Resource')
                    ->limit(35)
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Progress Status')
                    ->badge()
                    ->color(fn ($state) => match ($state?->value ?? $state) {
                        'completed' => 'success',
                        'downloaded' => 'info',
                        'opened' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('completed_at')
                    ->label('Completed')
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->label('Last Activity')
                    ->dateTime(),
            ])
            ->defaultSort('updated_at', 'desc');
    }
}
