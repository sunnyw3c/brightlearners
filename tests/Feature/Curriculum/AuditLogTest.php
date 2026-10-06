<?php

use App\Domains\Curriculum\Models\Subject;
use App\Support\Models\AuditLog;

test('creating a subject writes an audit log entry', function () {
    $subject = Subject::factory()->create(['name' => 'Science']);

    $log = AuditLog::query()->where('subject_type', $subject->getMorphClass())
        ->where('subject_id', $subject->id)
        ->where('action', 'subject.created')
        ->first();

    expect($log)->not->toBeNull();
    expect($log->after['name'])->toBe('Science');
});

test('archiving a subject writes an audit log entry with the change', function () {
    $subject = Subject::factory()->create(['active' => true]);

    $subject->update(['active' => false]);

    $log = AuditLog::query()->where('subject_type', $subject->getMorphClass())
        ->where('subject_id', $subject->id)
        ->where('action', 'subject.updated')
        ->latest('id')
        ->first();

    expect($log)->not->toBeNull();
    expect($log->before['active'])->toBeTrue();
    expect($log->after['active'])->toBeFalse();
});
