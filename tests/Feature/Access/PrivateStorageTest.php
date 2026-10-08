<?php

test('The resources storage disk is configured as a private disk and not publicly accessible', function () {
    $diskConfig = config('filesystems.disks.resources');

    expect($diskConfig)->not->toBeNull();
    // Driver should be local/s3 with private visibility or private path outside web root
    $visibility = $diskConfig['visibility'] ?? 'private';
    expect($visibility)->toBe('private');

    // Confirm no public route directly serves files from resources disk
    $publicPath = public_path('resources');
    expect(file_exists($publicPath))->toBeFalse();
});
