<?php

use App\Models\User;

test('the command creates a verified super admin', function () {
    $this->artisan('staff:create-super-admin')
        ->expectsQuestion('Name', 'Ada Admin')
        ->expectsQuestion('Email', 'ada@example.com')
        ->expectsQuestion('Password', 'a-secure-password')
        ->expectsOutput('Super-admin created.')
        ->assertSuccessful();

    $user = User::query()->where('email', 'ada@example.com')->firstOrFail();

    expect($user->name)->toBe('Ada Admin')
        ->and($user->hasVerifiedEmail())->toBeTrue()
        ->and($user->hasRole('super-admin'))->toBeTrue();
});
