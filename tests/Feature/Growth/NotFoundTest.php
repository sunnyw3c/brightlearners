<?php

use App\Domains\Curriculum\Models\SchoolClass;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('non-existent path returns HTTP 404 status, not 200', function () {
    $response = $this->get('/a-random-missing-url-path');

    $response->assertNotFound();
});

test('non-existent parent hub category returns HTTP 404', function () {
    $response = $this->get('/parents/non-existent-category');

    $response->assertNotFound();
});

test('non-existent article returns HTTP 404', function () {
    $response = $this->get('/blog/non-existent-article-slug');

    $response->assertNotFound();
});

test('non-existent product returns HTTP 404', function () {
    $schoolClass = SchoolClass::firstOrCreate(
        ['slug' => 'class-1'],
        ['name' => 'Class 1', 'sort_order' => 1, 'active' => true]
    );

    $response = $this->get("/shop/{$schoolClass->slug}/missing-product");

    $response->assertNotFound();
});
