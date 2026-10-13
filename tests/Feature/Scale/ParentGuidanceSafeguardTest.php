<?php

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Learning\Actions\GenerateParentGuidance;
use App\Domains\Learning\Models\ParentGuidanceLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('safe pedagogical queries return curriculum-grounded guidance with support escalation path', function () {
    $user = User::factory()->create();
    $class = SchoolClass::factory()->create(['name' => 'Class 2']);
    $profile = LearningProfile::factory()->create([
        'user_id' => $user->id,
        'nickname' => 'Aarav',
        'class_id' => $class->id,
    ]);

    $action = app(GenerateParentGuidance::class);
    $log = $action->handle($user, 'How can I help Aarav with daily math practice?', $profile);

    expect($log)->toBeInstanceOf(ParentGuidanceLog::class)
        ->and($log->user_id)->toBe($user->id)
        ->and($log->profile_id)->toBe($profile->id)
        ->and($log->escalated)->toBeFalse()
        ->and($log->safety_flags)->toBeNull()
        ->and($log->guidance)->toContain('Aarav')
        ->and($log->guidance)->toContain('Class 2')
        ->and($log->guidance)->toContain('support@brightlearners.in');
});

test('queries with clinical or medical terms are rejected with qualified professional referral', function () {
    $user = User::factory()->create();
    $action = app(GenerateParentGuidance::class);

    $queries = [
        'Does my child have adhd because he cannot sit still?',
        'Can you diagnose dyslexia based on spelling errors?',
        'What medication or therapy helps with learning disorders?',
    ];

    foreach ($queries as $query) {
        $log = $action->handle($user, $query);

        expect($log->escalated)->toBeTrue()
            ->and($log->safety_flags)->not->toBeNull()
            ->and($log->safety_flags['flag'])->toBe('clinical_diagnostic_restriction')
            ->and($log->guidance)->toContain('We do not provide clinical assessments, developmental diagnoses, or medical evaluations')
            ->and($log->guidance)->toContain('qualified healthcare professional')
            ->and($log->guidance)->toContain('support@brightlearners.in');
    }
});
