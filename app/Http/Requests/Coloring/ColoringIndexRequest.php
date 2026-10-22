<?php

namespace App\Http\Requests\Coloring;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ColoringIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'theme' => ['nullable', 'string', 'max:50'],
            'class' => ['nullable', 'string', 'exists:classes,slug'],
            'q' => ['nullable', 'string', 'max:120'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    /**
     * @return array{theme: ?string, class: ?string, q: ?string}
     */
    public function filters(): array
    {
        return [
            'theme' => $this->validated('theme'),
            'class' => $this->validated('class'),
            'q' => $this->validated('q'),
        ];
    }

    public function hasActiveFilters(): bool
    {
        return collect($this->filters())->filter(fn (?string $value) => filled($value))->isNotEmpty();
    }
}
