<?php

test('staging responses prevent search indexing', function () {
    $this->app->detectEnvironment(fn (): string => 'staging');

    $this->get(route('home'))
        ->assertOk()
        ->assertHeader('X-Robots-Tag', 'noindex, nofollow');
});

test('local responses do not set a robots header', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertHeaderMissing('X-Robots-Tag');
});
