<?php

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Curriculum\Models\Subject;
use App\Domains\Learning\Actions\ScoreAssessmentAttempt;
use App\Domains\Learning\Enums\AssessmentStatus;
use App\Domains\Learning\Enums\AttemptStatus;
use App\Domains\Learning\Enums\QuestionType;
use App\Domains\Learning\Enums\SkillBand;
use App\Domains\Learning\Models\Assessment;
use App\Domains\Learning\Models\AssessmentAttempt;
use App\Domains\Learning\Models\AssessmentQuestion;
use App\Domains\Learning\Models\AssessmentSection;
use App\Domains\Learning\Models\SkillScore;
use App\Models\User;

test('scoring is deterministic: identical answers produce identical score and bands', function () {
    $class = SchoolClass::factory()->create();
    $subject = Subject::factory()->create();
    $skill = Skill::factory()->create();

    $assessment = Assessment::factory()->create([
        'class_id' => $class->id,
        'subject_id' => $subject->id,
        'version' => 1,
        'status' => AssessmentStatus::Published,
    ]);

    $section = AssessmentSection::factory()->create(['assessment_id' => $assessment->id]);

    $q1 = AssessmentQuestion::factory()->create([
        'section_id' => $section->id,
        'skill_id' => $skill->id,
        'type' => QuestionType::SingleChoice,
        'points' => 1,
        'answer_config' => ['options' => ['A', 'B'], 'correct_answer' => 'A'],
    ]);

    $q2 = AssessmentQuestion::factory()->create([
        'section_id' => $section->id,
        'skill_id' => $skill->id,
        'type' => QuestionType::SingleChoice,
        'points' => 1,
        'answer_config' => ['options' => ['C', 'D'], 'correct_answer' => 'C'],
    ]);

    $user = User::factory()->create();
    $profile = LearningProfile::factory()->create(['user_id' => $user->id, 'class_id' => $class->id]);

    $attempt1 = AssessmentAttempt::factory()->create([
        'user_id' => $user->id,
        'learning_profile_id' => $profile->id,
        'assessment_id' => $assessment->id,
        'assessment_version' => 1,
    ]);

    $scorer = app(ScoreAssessmentAttempt::class);
    $answers = [$q1->id => 'A', $q2->id => 'C'];

    $scored1 = $scorer->handle($attempt1, $answers);

    expect($scored1->status)->toBe(AttemptStatus::Completed);
    $scoreRow1 = SkillScore::where('learning_profile_id', $profile->id)->where('skill_id', $skill->id)->first();
    expect($scoreRow1->band)->toBe(SkillBand::Confident);

    // Re-run scoring with identical answers on another attempt
    $attempt2 = AssessmentAttempt::factory()->create([
        'user_id' => $user->id,
        'learning_profile_id' => $profile->id,
        'assessment_id' => $assessment->id,
        'assessment_version' => 1,
    ]);

    $scored2 = $scorer->handle($attempt2, $answers);
    $scoreRow2 = SkillScore::where('source_attempt_id', $attempt2->id)->first();

    expect($scoreRow2->score)->toBe($scoreRow1->score)
        ->and($scoreRow2->band)->toBe($scoreRow1->band);
});

test('results are mapped to simple bands per skill and weak answers yield NeedsPractice band', function () {
    $class = SchoolClass::factory()->create();
    $subject = Subject::factory()->create();
    $skill = Skill::factory()->create();

    $assessment = Assessment::factory()->create([
        'class_id' => $class->id,
        'subject_id' => $subject->id,
        'version' => 1,
    ]);

    $section = AssessmentSection::factory()->create(['assessment_id' => $assessment->id]);

    $q1 = AssessmentQuestion::factory()->create([
        'section_id' => $section->id,
        'skill_id' => $skill->id,
        'type' => QuestionType::SingleChoice,
        'points' => 1,
        'answer_config' => ['options' => ['A', 'B'], 'correct_answer' => 'A'],
    ]);

    $q2 = AssessmentQuestion::factory()->create([
        'section_id' => $section->id,
        'skill_id' => $skill->id,
        'type' => QuestionType::SingleChoice,
        'points' => 1,
        'answer_config' => ['options' => ['C', 'D'], 'correct_answer' => 'C'],
    ]);

    $user = User::factory()->create();
    $profile = LearningProfile::factory()->create(['user_id' => $user->id, 'class_id' => $class->id]);

    $attempt = AssessmentAttempt::factory()->create([
        'user_id' => $user->id,
        'learning_profile_id' => $profile->id,
        'assessment_id' => $assessment->id,
    ]);

    // Give wrong answers (0 out of 2)
    $scorer = app(ScoreAssessmentAttempt::class);
    $scorer->handle($attempt, [$q1->id => 'B', $q2->id => 'D']);

    $scoreRow = SkillScore::where('source_attempt_id', $attempt->id)->first();
    expect($scoreRow->band)->toBe(SkillBand::NeedsPractice)
        ->and($scoreRow->band->label())->toBe('Needs Practice');
});

test('an assessment result cannot be reached from another user profile', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $profile1 = LearningProfile::factory()->create(['user_id' => $user1->id]);
    $assessment = Assessment::factory()->create(['status' => AssessmentStatus::Published]);

    $attempt = AssessmentAttempt::factory()->create([
        'user_id' => $user1->id,
        'learning_profile_id' => $profile1->id,
        'assessment_id' => $assessment->id,
        'status' => AttemptStatus::Completed,
        'completed_at' => now(),
    ]);

    // User 1 can view their result
    $this->actingAs($user1)
        ->get(route('assessments.result', ['attempt' => $attempt->id]))
        ->assertOk();

    // User 2 is denied access (403)
    $this->actingAs($user2)
        ->get(route('assessments.result', ['attempt' => $attempt->id]))
        ->assertForbidden();
});
