<?php

namespace Database\Factories;

use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceSkill;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ResourceSkill>
 */
#[UseModel(ResourceSkill::class)]
class ResourceSkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'resource_id' => LearningResource::factory(),
            'skill_id' => Skill::factory(),
            'class_id' => SchoolClass::factory(),
            'is_primary' => true,
        ];
    }
}
