<?php

namespace App\Http\Requests\Shop;

use App\Domains\Catalog\Enums\ProductType;
use App\Domains\Catalog\Queries\ShopIndexQuery;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validated filters for `/shop` and `/shop/{type}` (docs/plan/phase-06-product-catalogue.md,
 * step 6.11). Every field is an optional query-string parameter.
 */
class ShopIndexRequest extends FormRequest
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
            'type' => ['nullable', 'string', Rule::in(ProductType::routeSegments())],
            'price_band' => ['nullable', 'string', Rule::in(array_keys(ShopIndexQuery::PRICE_BANDS))],
            'q' => ['nullable', 'string', 'max:120'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    /**
     * @return array{class: ?string, subject: ?string, type: ?string, price_band: ?string, q: ?string}
     */
    public function filters(): array
    {
        return [
            'class' => $this->validated('class'),
            'subject' => $this->validated('subject'),
            'type' => $this->validated('type'),
            'price_band' => $this->validated('price_band'),
            'q' => $this->validated('q'),
        ];
    }

    public function hasActiveFilters(): bool
    {
        return collect($this->filters())->filter(fn (?string $value) => filled($value))->isNotEmpty();
    }
}
