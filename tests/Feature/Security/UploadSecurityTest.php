<?php

use App\Domains\Content\Actions\UploadResourceVersion;
use App\Domains\Content\Models\LearningResource;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('malicious or disallowed script file uploads are rejected', function () {
    Storage::fake('resources');

    $uploader = User::factory()->create();
    $resource = LearningResource::factory()->create(['created_by' => $uploader->id]);

    $maliciousFiles = [
        UploadedFile::fake()->create('exploit.php', 10, 'application/x-php'),
        UploadedFile::fake()->create('script.sh', 10, 'application/x-sh'),
        UploadedFile::fake()->create('malware.exe', 10, 'application/x-msdownload'),
        UploadedFile::fake()->create('payload.pdf.php', 10, 'application/x-php'),
    ];

    foreach ($maliciousFiles as $file) {
        expect(fn () => app(UploadResourceVersion::class)->handle($resource, $file, $uploader))
            ->toThrow(InvalidArgumentException::class);
    }

    Storage::disk('resources')->assertDirectoryEmpty((string) $resource->id);
});
