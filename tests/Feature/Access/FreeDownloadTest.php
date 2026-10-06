<?php

use App\Domains\Access\Events\ResourceDownloaded;
use App\Domains\Content\Enums\ResourceStatus;
use App\Models\User;
use Illuminate\Support\Facades\Event;

test('a free, published resource can be downloaded without logging in', function () {
    Event::fake([ResourceDownloaded::class]);
    ['resource' => $resource] = createPublishedFreeResource();

    $response = $this->get("/download/{$resource->slug}");

    $response->assertRedirect();
    expect($response->headers->get('Location'))->toContain('signature=');

    Event::assertDispatched(
        ResourceDownloaded::class,
        fn (ResourceDownloaded $event): bool => $event->resource->is($resource) && $event->userId === null,
    );
});

test('a logged-in parent downloading a free resource is recorded as the user, not anonymous', function () {
    Event::fake([ResourceDownloaded::class]);
    ['resource' => $resource] = createPublishedFreeResource();
    $user = User::factory()->create();

    $this->actingAs($user)->get("/download/{$resource->slug}")->assertRedirect();

    Event::assertDispatched(
        ResourceDownloaded::class,
        fn (ResourceDownloaded $event): bool => $event->userId === $user->id,
    );
});

test('a paid resource cannot be downloaded as free', function () {
    ['resource' => $resource] = createPublishedFreeResource(['is_free' => false]);

    $this->get("/download/{$resource->slug}")->assertForbidden();
});

test('an unpublished resource cannot be downloaded', function () {
    ['resource' => $resource] = createPublishedFreeResource();
    $resource->update(['status' => ResourceStatus::Draft]);

    $this->get("/download/{$resource->slug}")->assertNotFound();
});

test('an unknown resource slug is not found', function () {
    $this->get('/download/does-not-exist')->assertNotFound();
});
