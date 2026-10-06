<?php

namespace App\Http\Requests\Account;

use App\Domains\Curriculum\Models\SchoolClass;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLearningProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('profile'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nickname' => ['required', 'string', 'max:40'],
            'class_id' => ['nullable', Rule::exists(SchoolClass::class, 'id')->where('active', true)],
            'avatar_key' => ['nullable', Rule::in(config('account.avatar_keys'))],
            'interests' => ['nullable', 'array'],
            'interests.*' => ['string', 'max:40'],
        ];
    }
}
