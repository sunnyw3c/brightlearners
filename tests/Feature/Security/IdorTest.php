<?php

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Commerce\Models\Order;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use App\Models\User;

test('a user cannot access another user order details via IDOR', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $order = Order::factory()->create(['user_id' => $owner->id]);

    $this->actingAs($attacker)
        ->get(route('account.orders.show', $order))
        ->assertForbidden();
});

test('a user cannot update another user learning profile via IDOR', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $profile = LearningProfile::factory()->for($owner)->create(['nickname' => 'Original']);

    $this->actingAs($attacker)
        ->patch(route('account.profiles.update', $profile), [
            'nickname' => 'Attacked',
        ])
        ->assertForbidden();

    expect($profile->refresh()->nickname)->toBe('Original');
});

test('a user cannot delete another user learning profile via IDOR', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $profile = LearningProfile::factory()->for($owner)->create();

    $this->actingAs($attacker)
        ->delete(route('account.profiles.destroy', $profile))
        ->assertForbidden();

    expect($profile->refresh()->active)->toBeTrue();
});

test('a user cannot download a paid resource without an entitlement', function () {
    $user = User::factory()->create();
    $paidResource = LearningResource::factory()->published()->create(['is_free' => false]);
    ResourceVersion::factory()->create([
        'resource_id' => $paidResource->id,
        'is_current' => true,
        'file_path' => 'resources/test.pdf',
    ]);

    $this->actingAs($user)
        ->get(route('downloads.show', $paidResource))
        ->assertForbidden();
});
