<?php

namespace App\Domains\Curriculum\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ClassTopic extends Pivot
{
    protected $table = 'class_topic';

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
