<?php

use App\Domains\Accounts\Enums\FeedbackCategory;
use App\Domains\Accounts\Models\Feedback;
use App\Domains\Accounts\Models\SupportNote;
use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Enums\ProductType;
use App\Domains\Catalog\Models\Product;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Enums\ResourceType;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Membership\Enums\LearningProgramStatus;
use App\Domains\Membership\Models\LearningProgram;
use App\Domains\Membership\Models\Subscription;
use App\Domains\Membership\Models\SubscriptionPlan;
use App\Filament\Pages\Analytics\PilotCohort;
use App\Models\User;
use Database\Seeders\CurriculumSeeder;
use Database\Seeders\PilotContentSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\SubscriptionPlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('pilot cohort page is accessible to authorized staff only', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    $regularUser = User::factory()->create();

    $this->actingAs($regularUser)
        ->get('/admin/pilot-cohort')
        ->assertForbidden();

    $this->actingAs($admin)
        ->get('/admin/pilot-cohort')
        ->assertSuccessful();
});

test('pilot cohort table calculates real support and feedback contact count', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    $family = User::factory()->create();

    // Create 2 support notes and 1 feedback submission
    SupportNote::create([
        'user_id' => $family->id,
        'author_id' => $admin->id,
        'note' => 'Assisted with initial login and printer setup.',
    ]);
    SupportNote::create([
        'user_id' => $family->id,
        'author_id' => $admin->id,
        'note' => 'Follow up on worksheet completion.',
    ]);
    Feedback::create([
        'user_id' => $family->id,
        'category' => FeedbackCategory::Ux,
        'rating' => 4,
        'message' => 'Great pilot experience so far.',
        'status' => 'new',
    ]);

    // Create a subscription so the user appears in the cohort query
    $plan = SubscriptionPlan::factory()->create(['code' => 'pilot_90']);
    Subscription::factory()->create([
        'user_id' => $family->id,
        'plan_id' => $plan->id,
    ]);

    $this->actingAs($admin);

    Livewire::test(PilotCohort::class)
        ->assertCanSeeTableRecords([$family])
        ->assertTableColumnStateSet('support_contacts', 3, $family);
});

test('pilot cohort table filters by subscription plan code', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    $pilotPlan = SubscriptionPlan::factory()->create(['code' => 'pilot_90']);
    $monthlyPlan = SubscriptionPlan::factory()->create(['code' => 'monthly']);

    $pilotFamily = User::factory()->create();
    Subscription::factory()->create([
        'user_id' => $pilotFamily->id,
        'plan_id' => $pilotPlan->id,
    ]);

    $otherFamily = User::factory()->create();
    Subscription::factory()->create([
        'user_id' => $otherFamily->id,
        'plan_id' => $monthlyPlan->id,
    ]);

    $this->actingAs($admin);

    Livewire::test(PilotCohort::class)
        ->filterTable('plan', 'pilot_90')
        ->assertCanSeeTableRecords([$pilotFamily])
        ->assertCanNotSeeTableRecords([$otherFamily]);
});

test('pilot content seeder successfully provisions Phase 16 content catalogue', function () {
    $this->seed([
        RolesAndPermissionsSeeder::class,
        CurriculumSeeder::class,
        SubscriptionPlanSeeder::class,
        PilotContentSeeder::class,
    ]);

    // 16.1: 12 topic packs (4 for each of Class 1, 2, 3)
    $classes = SchoolClass::query()->whereIn('name', ['Class 1', 'Class 2', 'Class 3'])->get();
    expect($classes)->toHaveCount(3);

    $topicPacks = Product::query()
        ->where('type', ProductType::TopicPack)
        ->where('status', ProductStatus::Active)
        ->get();
    expect($topicPacks)->toHaveCount(12);

    foreach ($classes as $class) {
        $classPacks = $topicPacks->where('primary_class_id', $class->id);
        expect($classPacks)->toHaveCount(4);
    }

    // 16.2: 2 checked flagship ebooks and 3 bundles
    $ebooks = Product::query()
        ->where('type', ProductType::ActivityEbook)
        ->where('status', ProductStatus::Active)
        ->get();
    expect($ebooks)->toHaveCount(2);

    $bundles = Product::query()
        ->where('type', ProductType::Bundle)
        ->where('status', ProductStatus::Active)
        ->get();
    expect($bundles)->toHaveCount(3);

    // 16.3: 15–20 useful free resources and 3 class starter checks
    $freeResources = LearningResource::query()
        ->where('is_free', true)
        ->where('status', ResourceStatus::Published)
        ->get();
    expect($freeResources->count())->toBeGreaterThanOrEqual(18);

    $starterChecks = $freeResources->where('type', ResourceType::StarterCheck);
    expect($starterChecks)->toHaveCount(3);

    // 16.4: Complete membership month for each launch class + buffer month
    $publishedPrograms = LearningProgram::query()
        ->where('status', LearningProgramStatus::Published)
        ->get();
    expect($publishedPrograms)->toHaveCount(3);

    $bufferPrograms = LearningProgram::query()
        ->where('status', LearningProgramStatus::Ready)
        ->get();
    expect($bufferPrograms)->toHaveCount(3);
});
