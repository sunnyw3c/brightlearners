<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LearningResourceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'difficulty' => $this->difficulty,
            'estimated_minutes' => $this->estimated_minutes,
            'page_count' => $this->page_count,
            'is_free' => (bool) $this->is_free,
            'canonical_url' => $this->canonicalUrl(),
        ];
    }
}
