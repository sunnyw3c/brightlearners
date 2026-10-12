<?php

use App\Domains\Analytics\Enums\EventName;
use App\Domains\Analytics\Models\AnalyticsEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('a free-download event is tied to the download request and does not duplicate because of a page re-render', function () {
    Storage::fake('resources');
    Storage::disk('resources')->put('worksheets/test.pdf', 'fake-pdf-content');

    $setup = createPublishedFreeResource([
        'slug' => 'sample-free-counting',
        'title' => 'Sample Free Counting',
    ]);

    $resource = $setup['resource'];
    $class = $setup['class'];
    $subject = $setup['subject'];

    Storage::disk('resources')->put($resource->currentVersion->file_path, 'fake-pdf-content');

    // 1. Visit free detail page first time
    $response1 = $this->get(route('free.show', [
        'class' => $class->slug,
        'subject' => $subject->slug,
        'slug' => $resource->slug,
    ]));
    $response1->assertOk();

    // 2. Re-render the page (visit again)
    $response2 = $this->get(route('free.show', [
        'class' => $class->slug,
        'subject' => $subject->slug,
        'slug' => $resource->slug,
    ]));
    $response2->assertOk();

    // Confirm free_download has NOT fired from page views
    expect(AnalyticsEvent::query()->where('event_name', EventName::FreeDownload->value)->count())->toBe(0)
        ->and(AnalyticsEvent::query()->where('event_name', EventName::ResourceView->value)->count())->toBe(2);

    // 3. Initiate the actual signed download
    $downloadResponse = $this->get(route('downloads.show', ['resource' => $resource->slug]));
    $downloadResponse->assertRedirect();

    // Now free_download MUST fire exactly once
    expect(AnalyticsEvent::query()->where('event_name', EventName::FreeDownload->value)->count())->toBe(1);

    // 4. Re-render the page again after downloading
    $response3 = $this->get(route('free.show', [
        'class' => $class->slug,
        'subject' => $subject->slug,
        'slug' => $resource->slug,
    ]));
    $response3->assertOk();

    // Still exactly 1 free_download
    expect(AnalyticsEvent::query()->where('event_name', EventName::FreeDownload->value)->count())->toBe(1);
});
