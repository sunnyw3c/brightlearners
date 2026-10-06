<?php

use Inertia\Testing\AssertableInertia as Assert;

test('the canonical url uses the primary class and subject', function () {
    ['resource' => $resource, 'class' => $class, 'subject' => $subject] = createPublishedFreeResource();

    $canonical = route('free.show', [
        'class' => $class->slug,
        'subject' => $subject->slug,
        'slug' => $resource->slug,
    ]);

    $this->get($canonical)
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('free/show')
            ->where('seo.canonical', $canonical));
});

test('a non-canonical class redirects to the canonical url', function () {
    ['resource' => $resource, 'class' => $class, 'subject' => $subject] = createPublishedFreeResource();

    $canonical = route('free.show', [
        'class' => $class->slug,
        'subject' => $subject->slug,
        'slug' => $resource->slug,
    ]);

    $this->get("/free/class-does-not-match/{$subject->slug}/{$resource->slug}")
        ->assertRedirect($canonical);
});

test('a non-canonical subject redirects to the canonical url', function () {
    ['resource' => $resource, 'class' => $class, 'subject' => $subject] = createPublishedFreeResource();

    $canonical = route('free.show', [
        'class' => $class->slug,
        'subject' => $subject->slug,
        'slug' => $resource->slug,
    ]);

    $this->get("/free/{$class->slug}/subject-does-not-match/{$resource->slug}")
        ->assertRedirect($canonical);
});
