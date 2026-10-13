<?php

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceSkill;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Learning\Actions\GenerateRecommendations;
use App\Domains\Learning\Enums\RecommendationReason;
use App\Domains\Learning\Enums\SkillBand;
use App\Domains\Learning\Models\Assessment;
use App\Domains\Learning\Models\AssessmentAttempt;
use App\Domains\Learning\Models\Recommendation;
use App\Domains\Learning\Models\SkillScore;
use App\Models\User;

test('a recommendation never suggests content for the wrong class', function () {
    $class1 = SchoolClass::factory()->create(['slug' => 'class-1', 'name' => 'Class 1']);
    $class2 = SchoolClass::factory()->create(['slug' => 'class-2', 'name' => 'Class 2']);

    $skill = Skill::factory()->create();

    // Create resource for Class 2 only
    $resourceClass2 = LearningResource::factory()->create([
        'status' => ResourceStatus::Published,
    ]);
    ResourceSkill::factory()->create([
        'resource_id' => $resourceClass2->id,
        'skill_id' => $skill->id,
        'class_id' => $class2->id,
        'is_primary' => true,
    ]);

    $user = User::factory()->create();
    $childInClass1 = LearningProfile::factory()->create([
        'user_id' => $user->id,
        'class_id' => $class1->id,
    ]);

    // Record weak skill score for child in Class 1
    $assessment = Assessment::factory()->create(['class_id' => $class1->id]);
    $attempt = AssessmentAttempt::factory()->create([
        'user_id' => $user->id,
        'learning_profile_id' => $childInClass1->id,
        'assessment_id' => $assessment->id,
    ]);

    SkillScore::factory()->create([
        'learning_profile_id' => $childInClass1->id,
        'skill_id' => $skill->id,
        'score' => 40,
        'band' => SkillBand::NeedsPractice,
        'source_attempt_id' => $attempt->id,
    ]);

    $generator = app(GenerateRecommendations::class);
    $recs = $generator->handle($childInClass1);

    // Must NOT recommend Class 2's resource to a Class 1 child
    expect($recs->pluck('target_id')->contains($resourceClass2->id))->toBeFalse();
});

test('a recommendation never suggests an unpublished draft resource', function () {
    $class = SchoolClass::factory()->create();
    $skill = Skill::factory()->create();

    $draftResource = LearningResource::factory()->create([
        'status' => ResourceStatus::Draft,
    ]);
    ResourceSkill::factory()->create([
        'resource_id' => $draftResource->id,
        'skill_id' => $skill->id,
        'class_id' => $class->id,
        'is_primary' => true,
    ]);

    $user = User::factory()->create();
    $child = LearningProfile::factory()->create([
        'user_id' => $user->id,
        'class_id' => $class->id,
    ]);

    $assessment = Assessment::factory()->create(['class_id' => $class->id]);
    $attempt = AssessmentAttempt::factory()->create([
        'user_id' => $user->id,
        'learning_profile_id' => $child->id,
        'assessment_id' => $assessment->id,
    ]);

    SkillScore::factory()->create([
        'learning_profile_id' => $child->id,
        'skill_id' => $skill->id,
        'score' => 30,
        'band' => SkillBand::NeedsPractice,
        'source_attempt_id' => $attempt->id,
    ]);

    $generator = app(GenerateRecommendations::class);
    $recs = $generator->handle($child);

    expect($recs->pluck('target_id')->contains($draftResource->id))->toBeFalse();
});

test('recommendations store reason code mapping to parent-friendly explanation sentence', function () {
    $class = SchoolClass::factory()->create(['name' => 'Class 1']);
    $skill = Skill::factory()->create(['name' => 'Addition within 20']);

    $resource = LearningResource::factory()->create(['status' => ResourceStatus::Published]);
    ResourceSkill::factory()->create([
        'resource_id' => $resource->id,
        'skill_id' => $skill->id,
        'class_id' => $class->id,
        'is_primary' => true,
    ]);

    $user = User::factory()->create();
    $child = LearningProfile::factory()->create([
        'user_id' => $user->id,
        'class_id' => $class->id,
    ]);

    $attempt = AssessmentAttempt::factory()->create([
        'user_id' => $user->id,
        'learning_profile_id' => $child->id,
    ]);

    SkillScore::factory()->create([
        'learning_profile_id' => $child->id,
        'skill_id' => $skill->id,
        'score' => 50,
        'band' => SkillBand::NeedsPractice,
        'source_attempt_id' => $attempt->id,
    ]);

    $generator = app(GenerateRecommendations::class);
    $recs = $generator->handle($child);

    $rec = $recs->firstWhere('skill_id', $skill->id);
    expect($rec)->not->toBeNull();
    expect($rec->reason_code)->toBe(RecommendationReason::WeakSkillPractice->value);
    expect($rec->reasonSentence())->toContain('Addition within 20');
});

test('recommendation click and act tracking updates timestamps and requires ownership', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $child = LearningProfile::factory()->create(['user_id' => $user1->id]);
    $rec = Recommendation::factory()->create([
        'learning_profile_id' => $child->id,
        'clicked_at' => null,
        'acted_at' => null,
    ]);

    // Another user cannot record clicks on user 1's recommendation
    $this->actingAs($user2)
        ->postJson(route('account.recommendations.click', ['recommendation' => $rec->id]))
        ->assertForbidden();

    // Owner can track click
    $this->actingAs($user1)
        ->postJson(route('account.recommendations.click', ['recommendation' => $rec->id]))
        ->assertOk();

    expect($rec->fresh()->clicked_at)->not->toBeNull();

    // Owner can mark acted
    $this->actingAs($user1)
        ->postJson(route('account.recommendations.act', ['recommendation' => $rec->id]))
        ->assertOk();

    expect($rec->fresh()->acted_at)->not->toBeNull();
});
