<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

#[Signature('staff:create-super-admin')]
#[Description('Create a verified user with the super-admin role')]
class CreateSuperAdmin extends Command
{
    public function handle(): int
    {
        $name = (string) $this->ask('Name');
        $email = (string) $this->ask('Email');
        $password = (string) $this->secret('Password');

        $validator = Validator::make(compact('name', 'email', 'password'), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', Password::min(12)],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user = User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        $user->forceFill(['email_verified_at' => now()])->save();

        $user->assignRole(Role::findOrCreate('super-admin', 'web'));

        $this->info('Super-admin created.');

        return self::SUCCESS;
    }
}
