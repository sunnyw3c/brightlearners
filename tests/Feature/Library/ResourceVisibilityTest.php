<?php

use App\Domains\Content\Enums\ResourceStatus;

test('a draft resource is not visible on its free page', function () {
    ['resource' => $resource, 'class' => $class, 'subject' => $subject] = createPublishedFreeResource();
    $resource->update(['status' => ResourceStatus::Draft]);

    $this->get("/free/{$class->slug}/{$subject->slug}/{$resource->slug}")
        ->assertNotFound();
});

test('an archived resource is not visible on its free page', function () {
    ['resource' => $resource, 'class' => $class, 'subject' => $subject] = createPublishedFreeResource();
    $resource->update(['status' => ResourceStatus::Archived]);

    $this->get("/free/{$class->slug}/{$subject->slug}/{$resource->slug}")
        ->assertNotFound();
});

test('an unknown slug is not found', function () {
    ['class' => $class, 'subject' => $subject] = createPublishedFreeResource();

    $this->get("/free/{$class->slug}/{$subject->slug}/does-not-exist")
        ->assertNotFound();
});

test('a published, paid resource does not appear on the free hub', function () {
    ['resource' => $resource, 'class' => $class, 'subject' => $subject] = createPublishedFreeResource(['is_free' => false]);

    $this->get("/free/{$class->slug}/{$subject->slug}/{$resource->slug}")
        ->assertNotFound();
});
