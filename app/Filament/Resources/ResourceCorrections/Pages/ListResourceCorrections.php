<?php

namespace App\Filament\Resources\ResourceCorrections\Pages;

use App\Filament\Resources\ResourceCorrections\ResourceCorrectionResource;
use Filament\Resources\Pages\ListRecords;

class ListResourceCorrections extends ListRecords
{
    protected static string $resource = ResourceCorrectionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
