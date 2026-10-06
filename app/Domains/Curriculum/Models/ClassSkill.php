<?php

namespace App\Domains\Curriculum\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ClassSkill extends Pivot
{
    protected $table = 'class_skill';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
