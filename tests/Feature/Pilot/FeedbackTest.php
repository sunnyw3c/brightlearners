<?php

use App\Domains\Accounts\Enums\FeedbackCategory;
use App\Domains\Accounts\Models\Feedback;
use App\Domains\Analytics\Enums\EventName;
use App\Domains\Content\Models\LearningResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a parent can submit feedback across all categories', function (FeedbackCategory $category) {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->from('/learn')
        ->post(route('feedback.store'), [
            'category' => $category->value,
            'rating' => 5,
            'message' => 'The worksheet instructions were wonderful and easy to follow!',
        ]);

    $response->assertRedirect('/learn');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('feedback', [
        'user_id' => $user->id,
        'category' => $category->value,
        'rating' => 5,
        'status' => 'new',
    ]);

    $this->assertDatabaseHas('analytics_events', [
        'event_name' => EventName::FeedbackSubmitted->value,
        'user_id' => $user->id,
    ]);
})->with([
    FeedbackCategory::Content,
    FeedbackCategory::Ux,
    FeedbackCategory::Payment,
    FeedbackCategory::Support,
]);

test('guest users can submit feedback with referrer url', function () {
    $response = $this->from('/shop')
        ->post(route('feedback.store'), [
            'category' => 'ux',
            'rating' => 4,
            'message' => 'Very intuitive navigation on mobile browser.',
        ]);

    $response->assertRedirect('/shop');
    $response->assertSessionHas('success');

    $feedback = Feedback::query()->latest('id')->first();
    expect($feedback)->not->toBeNull()
        ->and($feedback->user_id)->toBeNull()
        ->and($feedback->category)->toBe(FeedbackCategory::Ux)
        ->and($feedback->url)->toContain('/shop');
});

test('feedback submission validates required fields and constraints', function () {
    $response = $this->post(route('feedback.store'), [
        'category' => 'invalid_cat',
        'rating' => 10,
        'message' => 'hi', // too short
        'learning_resource_id' => 999999, // non-existent
    ]);

    $response->assertSessionHasErrors(['category', 'rating', 'message', 'learning_resource_id']);
    expect(Feedback::query()->count())->toBe(0);
});

test('feedback links to learning resource when provided', function () {
    $user = User::factory()->create();
    $resource = LearningResource::factory()->published()->create();

    $response = $this->actingAs($user)
        ->post(route('feedback.store'), [
            'category' => 'content',
            'learning_resource_id' => $resource->id,
            'rating' => 3,
            'message' => 'Question 4 had a minor typo in the instructions.',
        ]);

    $response->assertSessionHas('success');

    $this->assertDatabaseHas('feedback', [
        'user_id' => $user->id,
        'learning_resource_id' => $resource->id,
        'category' => 'content',
        'rating' => 3,
    ]);
});
