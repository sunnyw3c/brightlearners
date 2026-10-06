<?php

namespace App\Support\Concerns;

use App\Support\Audit;
use Illuminate\Support\Str;

/**
 * Logs every create and edit to the audit log. Deletes are not covered
 * here because the curriculum models never hard-delete a referenced row
 * (see each model's policy); archiving is itself an edit (`active` turns
 * false) and is logged as one.
 */
trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function (self $model): void {
            Audit::record(static::auditAction('created'), $model, null, $model->getAttributes());
        });

        static::updated(function (self $model): void {
            Audit::record(static::auditAction('updated'), $model, $model->getOriginal(), $model->getChanges());
        });
    }

    protected static function auditAction(string $verb): string
    {
        return Str::snake(class_basename(static::class)).'.'.$verb;
    }
}
