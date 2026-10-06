<?php

namespace App\Domains\Accounts\Enums;

enum UserStatus: string
{
    case Active = 'active';
    case Suspended = 'suspended';
    case DeletionRequested = 'deletion_requested';
}
