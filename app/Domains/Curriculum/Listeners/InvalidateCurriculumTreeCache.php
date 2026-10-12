<?php

namespace App\Domains\Curriculum\Listeners;

use App\Domains\Curriculum\Queries\CurriculumTree;

class InvalidateCurriculumTreeCache
{
    public function handle(): void
    {
        CurriculumTree::flushCache();
    }
}
