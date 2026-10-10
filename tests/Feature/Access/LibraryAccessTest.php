<?php

use App\Domains\Access\Models\Download;
use App\Domains\Access\Models\Entitlement;
use App\Domains\Catalog\Enums\ProductType;
use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Models\Order;
use App\Domains\Commerce\Models\OrderItem;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Payments\Events\OrderPaid;
use App\Domains\Payments\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('One paid test order appears in My Library and downloads through a temporary URL', function () {
    $user = User::factory()->create();

    $resource = LearningResource::factory()->create([
        'title' => 'Fractions Mastery Worksheet',
        'slug' => 'fractions-mastery-worksheet',
        'status' => ResourceStatus::Published,
        'is_free' => false,
    ]);

    $version = ResourceVersion::factory()->create([
        'resource_id' => $resource->id,
        'version' => '1.0',
        'is_current' => true,
        'file_path' => 'resources/fractions.pdf',
    ]);

    $product = Product::factory()->create([
        'name' => 'Fractions Topic Pack',
        'type' => ProductType::TopicPack,
    ]);
    $product->resources()->attach($resource->id);

    // Create and pay order
    $order = Order::factory()->create([
        'user_id' => $user->id,
        'paid_at' => now(),
    ]);
    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
    ]);

    // Dispatch OrderPaid event to trigger entitlement grant
    OrderPaid::dispatch($order, Payment::factory()->create(['order_id' => $order->id]));

    // Entitlement granted
    expect(Entitlement::where('user_id', $user->id)->where('resource_id', $resource->id)->exists())->toBeTrue();

    // User visits My Library
    $this->actingAs($user)
        ->get(route('account.library.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('account/library/index')
            ->has('purchased', 1)
            ->where('purchased.0.title', 'Fractions Mastery Worksheet')
            ->where('purchased.0.slug', 'fractions-mastery-worksheet')
            ->where('purchased.0.current_version', '1.0')
        );

    // User visits Library detail page
    $this->actingAs($user)
        ->get(route('account.library.show', $resource->slug))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('account/library/show')
            ->where('resource.title', 'Fractions Mastery Worksheet')
            ->where('resource.current_version', '1.0')
            ->where('resource.has_correction_notice', false)
        );

    // User downloads the resource
    $downloadResponse = $this->actingAs($user)
        ->get(route('downloads.show', $resource->slug));

    $downloadResponse->assertRedirect();
    $redirectUrl = $downloadResponse->headers->get('Location');
    expect($redirectUrl)->toBeString();
    expect($redirectUrl)->toContain('signature=');

    // Download logged
    $download = Download::where('user_id', $user->id)->first();
    expect($download)->not->toBeNull();
    expect($download->resource_id)->toBe($resource->id);
    expect($download->resource_version_id)->toBe($version->id);
    expect($download->access_source)->toBe('purchase');
});

test('Library index shows correction notice when newer version published after user download', function () {
    $user = User::factory()->create();

    $resource = LearningResource::factory()->create([
        'status' => ResourceStatus::Published,
        'is_free' => false,
    ]);

    $v1 = ResourceVersion::factory()->create([
        'resource_id' => $resource->id,
        'version' => '1.0',
        'is_current' => false,
        'file_path' => 'resources/v1.pdf',
    ]);

    $v2 = ResourceVersion::factory()->create([
        'resource_id' => $resource->id,
        'version' => '2.0',
        'is_current' => true,
        'file_path' => 'resources/v2.pdf',
    ]);

    Entitlement::factory()->create([
        'user_id' => $user->id,
        'resource_id' => $resource->id,
    ]);

    // Download record for v1
    Download::factory()->create([
        'user_id' => $user->id,
        'resource_id' => $resource->id,
        'resource_version_id' => $v1->id,
        'downloaded_at' => now()->subDay(),
    ]);

    // Check library index flags correction notice
    $this->actingAs($user)
        ->get(route('account.library.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('account/library/index')
            ->where('purchased.0.has_correction_notice', true)
        );

    // Check library show flags correction notice
    $this->actingAs($user)
        ->get(route('account.library.show', $resource->slug))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('account/library/show')
            ->where('resource.has_correction_notice', true)
            ->where('resource.current_version', '2.0')
        );
});

test('Unentitled user cannot access library show for paid resource', function () {
    $user = User::factory()->create();

    $resource = LearningResource::factory()->create([
        'status' => ResourceStatus::Published,
        'is_free' => false,
    ]);
    ResourceVersion::factory()->create([
        'resource_id' => $resource->id,
        'is_current' => true,
    ]);

    $this->actingAs($user)
        ->get(route('account.library.show', $resource->slug))
        ->assertStatus(403);
});

test('Free resources downloaded by user appear in Free Downloads tab', function () {
    $user = User::factory()->create();

    $resource = LearningResource::factory()->create([
        'title' => 'Free Counting Sheet',
        'status' => ResourceStatus::Published,
        'is_free' => true,
    ]);
    $version = ResourceVersion::factory()->create([
        'resource_id' => $resource->id,
        'version' => '1.0',
        'is_current' => true,
    ]);

    // Download free resource
    $this->actingAs($user)->get(route('downloads.show', $resource->slug));

    expect(Download::where('user_id', $user->id)->count())->toBe(1);

    $this->actingAs($user)
        ->get(route('account.library.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('account/library/index')
            ->has('free', 1)
            ->where('free.0.title', 'Free Counting Sheet')
        );
});
