<?php

use App\Filament\Widgets\BusinessStatsOverviewWidget;
use App\Filament\Widgets\OperationsOverviewWidget;
use App\Filament\Widgets\QuickActionsWidget;
use App\Filament\Widgets\RecentOrdersWidget;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $this->admin = User::factory()->create([
        'email' => 'admin-dashboard-tester@brightlearners.in',
        'name' => 'Admin Dashboard Tester',
    ]);
    $this->admin->assignRole('super-admin');
});

test('admin dashboard renders successfully for authenticated super admin', function () {
    $this->actingAs($this->admin)
        ->get('/admin')
        ->assertOk();
});

test('admin dashboard widgets can be mounted and rendered', function () {
    $this->actingAs($this->admin);

    Livewire::test(BusinessStatsOverviewWidget::class)
        ->assertOk();

    Livewire::test(OperationsOverviewWidget::class)
        ->assertOk();

    Livewire::test(QuickActionsWidget::class)
        ->assertOk();

    Livewire::test(RecentOrdersWidget::class)
        ->assertOk();
});

test('quick actions view contains essential admin navigation links', function () {
    $this->actingAs($this->admin);

    Livewire::test(QuickActionsWidget::class)
        ->assertSee('/admin/learning-resources/create')
        ->assertSee('/admin/products/create')
        ->assertSee('/admin/coupons/create')
        ->assertSee('/admin/review-queue')
        ->assertSee('/admin/orders')
        ->assertSee('/admin/customers');
});
