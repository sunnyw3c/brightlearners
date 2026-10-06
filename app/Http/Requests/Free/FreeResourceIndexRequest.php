<?php

namespace App\Http\Requests\Free;

use App\Domains\Content\Enums\ResourceType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validated filters for the `/free` hub (step 5.9). Every field is an
 * optional query-string parameter.
 */
class FreeResourceIndexRequest extends FormRequest
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
            'class' => ['nullable', 'string', 'exists:classes,slug'],
            'subject' => ['nullable', 'string', 'exists:subjects,slug'],
            'topic' => ['nullable', 'string', 'exists:topics,slug'],
            'type' => ['nullable', 'string', Rule::in(array_map(fn (ResourceType $case): string => $case->value, ResourceType::cases()))],
            'difficulty' => ['nullable', 'string', 'max:40'],
            'price' => ['nullable', 'string', Rule::in(['free', 'paid'])],
            'q' => ['nullable', 'string', 'max:120'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    /**
     * @return array{class: ?string, subject: ?string, topic: ?string, type: ?string, difficulty: ?string, price: ?string, q: ?string}
     */
    public function filters(): array
    {
        return [
            'class' => $this->validated('class'),
            'subject' => $this->validated('subject'),
            'topic' => $this->validated('topic'),
            'type' => $this->validated('type'),
            'difficulty' => $this->validated('difficulty'),
            'price' => $this->validated('price'),
            'q' => $this->validated('q'),
        ];
    }

    public function hasActiveFilters(): bool
    {
        return collect($this->filters())->filter(fn (?string $value) => filled($value))->isNotEmpty();
    }
}
