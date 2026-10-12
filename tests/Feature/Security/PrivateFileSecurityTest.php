<?php

use Illuminate\Support\Facades\Storage;

afterEach(function () {
    Storage::disk('resources')->deleteDirectory('test-security-file');
});

test('private storage refuses unsigned direct file access', function () {
    Storage::disk('resources')->put('test-security-file/worksheet.pdf', 'secret-content');

    $this->get('/private-files/test-security-file/worksheet.pdf')->assertForbidden();
});

test('temporary signed URL provides access before expiry and refuses tampered signatures', function () {
    Storage::disk('resources')->put('test-security-file/worksheet.pdf', 'secret-content');

    $temporaryUrl = Storage::disk('resources')->temporaryUrl(
        'test-security-file/worksheet.pdf',
        now()->addMinutes(5),
    );

    // Valid temporary signed URL serves the file
    $this->get($temporaryUrl)->assertOk();

    // Tampered signature is refused
    $tamperedUrl = $temporaryUrl.'-invalid';
    $this->get($tamperedUrl)->assertForbidden();
});

test('expired signed URL is refused', function () {
    Storage::disk('resources')->put('test-security-file/worksheet.pdf', 'secret-content');

    $temporaryUrl = Storage::disk('resources')->temporaryUrl(
        'test-security-file/worksheet.pdf',
        now()->addMinutes(5),
    );

    $this->travel(10)->minutes();

    $this->get($temporaryUrl)->assertForbidden();
});
