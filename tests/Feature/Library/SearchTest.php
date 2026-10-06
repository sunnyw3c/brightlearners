<?php

use App\Domains\Content\Enums\ResourceStatus;
use Inertia\Testing\AssertableInertia as Assert;

test('search finds a resource by its exact title', function () {
    createPublishedFreeResource(['title' => 'Addition Practice Worksheet']);

    $this->get('/search?'.http_build_query(['q' => 'Addition Practice Worksheet']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('search/index')
            ->where('results.data.0.title', 'Addition Practice Worksheet'));
});

test('search finds a resource by a partial word', function () {
    createPublishedFreeResource(['title' => 'Addition Practice Worksheet']);

    $this->get('/search?q=addition')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('results.data.0.title', 'Addition Practice Worksheet'));
});

test('an unpublished resource never appears in search results', function () {
    ['resource' => $resource] = createPublishedFreeResource(['title' => 'Subtraction Practice Worksheet']);
    $resource->update(['status' => ResourceStatus::Draft]);

    $this->get('/search?q=Subtraction')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('results.data', []));
});

test('a paid resource never appears in search results', function () {
    createPublishedFreeResource(['title' => 'Multiplication Mastery Pack', 'is_free' => false]);

    $this->get('/search?q=Multiplication')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('results.data', []));
});

test('a blank search shows no results without querying', function () {
    createPublishedFreeResource();

    $this->get('/search')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('q', '')
            ->where('results.data', []));
});
