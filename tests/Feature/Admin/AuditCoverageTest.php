<?php

use App\Domains\Access\Actions\GrantManualEntitlement;
use App\Domains\Accounts\Actions\SyncStaffRoles;
use App\Domains\Catalog\Actions\ChangeProductPrice;
use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Models\Order;
use App\Domains\Content\Actions\PublishResourceVersion;
use App\Domains\Content\Actions\UploadResourceVersion;
use App\Domains\Content\Enums\PreviewStatus;
use App\Domains\Content\Enums\ReviewStatus;
use App\Domains\Content\Enums\ReviewType;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourcePreview;
use App\Domains\Content\Models\ResourceReview;
use App\Domains\Content\Models\ResourceSkill;
use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Payments\Actions\IssueRefund;
use App\Domains\Payments\Enums\PaymentStatus;
use App\Domains\Payments\Models\Payment;
use App\Models\User;
use App\Support\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('resources');
    Storage::fake('previews');

    Permission::findOrCreate('products.edit-price', 'web');
    Permission::findOrCreate('roles.manage', 'web');
    Role::findOrCreate('super-admin', 'web')->givePermissionTo(['products.edit-price', 'roles.manage']);
});

test('price change creates an audit log', function () {
    $admin = User::factory()->create();
    $admin->assignRole('super-admin');
    $this->actingAs($admin);

    $product = Product::factory()->create([
        'regular_price' => 50000,
        'sale_price' => null,
    ]);

    app(ChangeProductPrice::class)->handle($product, 65000, 55000);

    $log = AuditLog::query()
        ->where('action', 'price.changed')
        ->where('subject_id', $product->id)
        ->latest('id')
        ->first();

    expect($log)->not->toBeNull();
    expect($log->after['regular_price'])->toBe(65000);
    expect($log->after['sale_price'])->toBe(55000);
});

test('resource publishing creates an audit log', function () {
    $admin = User::factory()->create();
    $admin->assignRole('super-admin');
    $this->actingAs($admin);

    $uploader = User::factory()->create();
    $reviewer = User::factory()->create();

    $schoolClass = SchoolClass::factory()->create();
    $skill = Skill::factory()->create();

    $resource = LearningResource::factory()->create([
        'learning_objective' => 'Understand basic geometry shapes',
        'type' => 'worksheet',
        'page_count' => 5,
    ]);
    ResourceSkill::factory()->create([
        'resource_id' => $resource->id,
        'skill_id' => $skill->id,
        'class_id' => $schoolClass->id,
        'is_primary' => true,
    ]);

    $version = ResourceVersion::factory()->create([
        'resource_id' => $resource->id,
        'version' => '1.0',
        'created_by' => $uploader->id,
        'preview_status' => PreviewStatus::Ready,
        'is_current' => false,
        'published_at' => null,
    ]);

    ResourcePreview::factory()->create([
        'resource_version_id' => $version->id,
        'page_no' => 1,
    ]);

    foreach ([ReviewType::Educational, ReviewType::AnswerVerification, ReviewType::DesignPrint] as $type) {
        ResourceReview::factory()->create([
            'resource_version_id' => $version->id,
            'reviewer_id' => $reviewer->id,
            'review_type' => $type,
            'status' => ReviewStatus::Approved,
        ]);
    }

    app(PublishResourceVersion::class)->handle($version);

    $log = AuditLog::query()
        ->where('action', 'resource.published')
        ->where('subject_id', $resource->id)
        ->latest('id')
        ->first();

    expect($log)->not->toBeNull();
    expect($log->after['status'])->toBe('published');
});

test('new resource version upload creates an audit log', function () {
    $admin = User::factory()->create();
    $this->actingAs($admin);

    $resource = LearningResource::factory()->create();

    $file = UploadedFile::fake()->create('worksheet.pdf', 100, 'application/pdf');

    $version = app(UploadResourceVersion::class)->handle($resource, $file, $admin);

    $log = AuditLog::query()
        ->where('action', 'resource_version.created')
        ->where('subject_id', $version->id)
        ->latest('id')
        ->first();

    expect($log)->not->toBeNull();
    expect($log->after['version'])->toBe('1.0');
});

test('refund issuance creates an audit log', function () {
    $admin = User::factory()->create();
    $this->actingAs($admin);

    $order = Order::factory()->create([
        'status' => OrderStatus::Paid,
    ]);

    $payment = Payment::factory()->create([
        'order_id' => $order->id,
        'amount' => 49900,
        'status' => PaymentStatus::Captured,
    ]);

    $refund = app(IssueRefund::class)->handle($payment, 49900, 'Customer dissatisfied');

    $log = AuditLog::query()
        ->where('action', 'refund.issued')
        ->where('subject_id', $refund->id)
        ->latest('id')
        ->first();

    expect($log)->not->toBeNull();
    expect($log->after['amount'])->toBe(49900);
    expect($log->after['reason'])->toBe('Customer dissatisfied');
});

test('manual entitlement grant creates an audit log', function () {
    $admin = User::factory()->create();
    $this->actingAs($admin);

    $user = User::factory()->create();
    $resource = LearningResource::factory()->create();

    $entitlement = app(GrantManualEntitlement::class)->handle(
        $user,
        $resource,
        'Support Ticket #104: Courtesy access'
    );

    $log = AuditLog::query()
        ->where('action', 'entitlement.granted')
        ->where('subject_id', $entitlement->id)
        ->latest('id')
        ->first();

    expect($log)->not->toBeNull();
    expect($log->after['reason'])->toBe('Support Ticket #104: Courtesy access');
});

test('staff role change creates an audit log', function () {
    $admin = User::factory()->create();
    $admin->assignRole('super-admin');
    $this->actingAs($admin);

    Role::findOrCreate('content-manager', 'web');

    $targetUser = User::factory()->create();

    app(SyncStaffRoles::class)->handle($targetUser, ['content-manager'], $admin);

    $log = AuditLog::query()
        ->where('action', 'role.changed')
        ->where('subject_id', $targetUser->id)
        ->latest('id')
        ->first();

    expect($log)->not->toBeNull();
    expect($log->after['roles'])->toContain('content-manager');
});
