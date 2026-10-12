<?php

test('web responses include production-grade security headers', function () {
    $response = $this->get('/');

    $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    $response->assertHeader('X-Content-Type-Options', 'nosniff');
    $response->assertHeader('X-XSS-Protection', '1; mode=block');
    $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

    $csp = $response->headers->get('Content-Security-Policy-Report-Only');
    expect($csp)->not->toBeNull();
    expect($csp)->toContain("default-src 'self'");
    expect($csp)->toContain('https://checkout.razorpay.com');
    expect($csp)->toContain('https://www.google-analytics.com');
});

test('health endpoint returns deep dependency check status', function () {
    $response = $this->get('/health');

    $response->assertOk()
        ->assertJson([
            'status' => 'ok',
            'checks' => [
                'database' => 'ok',
                'cache' => 'ok',
                'queue' => 'ok',
                'storage' => 'ok',
            ],
        ]);
});

test('liveness endpoint returns 200', function () {
    $this->get('/up')->assertOk();
});
