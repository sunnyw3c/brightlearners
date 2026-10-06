<?php

use Illuminate\Support\Facades\Cache;

test('health endpoint confirms the database and cache are available', function () {
    $this->get(route('health'))
        ->assertOk()
        ->assertExactJson(['status' => 'ok']);
});

test('health endpoint returns no diagnostic details when a dependency fails', function () {
    Cache::shouldReceive('put')
        ->once()
        ->andThrow(new RuntimeException('Sensitive cache failure detail'));

    $this->get(route('health'))
        ->assertStatus(503)
        ->assertContent('');
});
