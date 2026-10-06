<?php

namespace App\Filament\Resources\LearningResources\Pages;

use App\Filament\Resources\LearningResources\LearningResourceResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreateLearningResource extends CreateRecord
{
    protected static string $resource = LearningResourceResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();
        $data['slug'] ??= Str::slug($data['title']);

        return $data;
    }
}
