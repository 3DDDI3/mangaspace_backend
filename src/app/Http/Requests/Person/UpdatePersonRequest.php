<?php

namespace App\Http\Requests\Person;

use App\Enums\PersonType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePersonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge($this->json()->all());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'persons' => ['nullable', 'array'],
            'persons.*.name' => ['required', 'string'],
            'persons.*.type' => ['required', 'integer', Rule::enum(PersonType::class)],
            'persons.*.description' => ['nullable', 'string'],
            'persons.*.image' => ['nullable', 'array'],
            'persons.*.image.*.path' => ['nullable', 'url'],
            'persons.*.image.*.extension' => ['nullable', Rule::in(['jpeg', 'jpg', 'webp', 'png'])],
            'persons.*.altName' => ['nullable', 'string'],
            'persons.*.url' => ['required', 'string'],
        ];
    }
}
