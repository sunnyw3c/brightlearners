<?php

use App\Domains\Membership\Models\SubscriptionPlan;
use App\Filament\Resources\SubscriptionPlans\Pages\EditSubscriptionPlan;
use App\Models\User;
use App\Support\Models\AuditLog;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Auth\Access\AuthorizationException;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $this->businessAdmin = User::factory()->create();
    $this->businessAdmin->assignRole('business-admin');
});

test('only a role with plans.edit-price can open the plans screens', function () {
    $plan = SubscriptionPlan::factory()->create();

    $contentManager = User::factory()->create();
    $contentManager->assignRole('content-manager');

    $this->actingAs($contentManager)->get('/admin/subscription-plans')->assertForbidden();

    $this->actingAs($this->businessAdmin)->get('/admin/subscription-plans')->assertOk();
    $this->actingAs($this->businessAdmin)->get('/admin/subscription-plans/create')->assertOk();
    $this->actingAs($this->businessAdmin)->get("/admin/subscription-plans/{$plan->id}/edit")->assertOk();
});

test('a business admin can change a plan price and the change is audited', function () {
    $plan = SubscriptionPlan::factory()->create(['price' => 49900]);

    $this->actingAs($this->businessAdmin);

    Livewire::test(EditSubscriptionPlan::class, ['record' => $plan->getRouteKey()])
        ->fillForm(['price' => 59900, 'benefits.member_discount_percent' => 20])
        ->call('save')
        ->assertHasNoFormErrors();

    $plan->refresh();
    expect($plan->price)->toBe(59900);
    expect($plan->benefits['member_discount_percent'])->toBe(20);

    $log = AuditLog::query()->where('action', 'subscription_plan.updated')->where('subject_id', $plan->id)->latest('id')->first();
    expect($log->before['price'])->toBe(49900);
    expect($log->after['price'])->toBe(59900);
});

test('a staff member without plans.edit-price cannot change a plan price at the model layer', function () {
    $plan = SubscriptionPlan::factory()->create(['price' => 49900]);

    $contentManager = User::factory()->create();
    $contentManager->assignRole('content-manager');
    $this->actingAs($contentManager);

    expect(fn () => $plan->update(['price' => 100]))->toThrow(AuthorizationException::class);
    expect($plan->refresh()->price)->toBe(49900);
});
