<?php

namespace App\Domains\Curriculum\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ClassSubject extends Pivot
{
    protected $table = 'class_subject';

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
