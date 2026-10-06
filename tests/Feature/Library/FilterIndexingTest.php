<?php

use Inertia\Testing\AssertableInertia as Assert;

test('the plain free hub is indexable', function () {
    createPublishedFreeResource();

    $this->get('/free')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('free/index')
            ->where('seo.noindex', false)
            ->where('seo.canonical', route('free.index')));
});

test('a filtered free hub url is noindex, with a canonical that drops the filters', function () {
    createPublishedFreeResource();

    $this->get('/free?type=worksheet')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('free/index')
            ->where('seo.noindex', true)
            ->where('seo.canonical', route('free.index')));
});

test('a search filter also carries noindex', function () {
    createPublishedFreeResource();

    $this->get('/free?q=addition')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('seo.noindex', true));
});
