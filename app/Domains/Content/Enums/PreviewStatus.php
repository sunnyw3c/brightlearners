<?php

namespace App\Domains\Content\Enums;

enum PreviewStatus: string
{
    case Pending = 'pending';
    case Ready = 'ready';
    case Failed = 'failed';
}
