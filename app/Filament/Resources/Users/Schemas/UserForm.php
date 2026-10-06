<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Domains\Accounts\Enums\UserStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                Select::make('status')
                    ->options(UserStatus::class)
                    ->default(UserStatus::Active)
                    ->required(),
                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->options(Role::query()->pluck('name', 'id'))
                    ->multiple()
                    ->preload()
                    ->helperText('A parent has no role. Only super-admin and business-admin can change this.'),
                TextInput::make('password')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->dehydrateStateUsing(fn (string $state): string => $state)
                    ->helperText('Leave blank to keep the current password.'),
            ]);
    }
}
