<?php

namespace App\Domains\Content\Enums;

enum ReviewStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case ChangesRequested = 'changes_requested';
}
