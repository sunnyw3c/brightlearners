<?php

namespace App\Domains\Catalog\Enums;

enum ProductStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Archived = 'archived';
}
