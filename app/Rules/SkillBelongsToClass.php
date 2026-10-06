<?php

namespace App\Rules;

use App\Domains\Curriculum\Models\SchoolClass;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

/**
 * Validates that the skill ID being validated is mapped, and active, for
 * the given class. Used wherever a resource is tagged to a (class, skill)
 * pair — first in Phase 4's resource form.
 */
class SkillBelongsToClass implements ValidationRule
{
    public function __construct(private readonly int|string|null $classId) {}

    /**
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->classId === null) {
            $fail('The :attribute must have a class to belong to.');

            return;
        }

        $belongs = SchoolClass::query()
            ->whereKey($this->classId)
            ->whereHas('skills', function ($query) use ($value): void {
                $query->whereKey($value)->where('class_skill.active', true);
            })
            ->exists();

        if (! $belongs) {
            $fail('The :attribute is not an active skill for the selected class.');
        }
    }
}
