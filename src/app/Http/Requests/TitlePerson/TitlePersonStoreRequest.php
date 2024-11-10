<?php

namespace App\Http\Requests\TitlePerson;

use Illuminate\Foundation\Http\FormRequest;

class TitlePersonStoreRequest extends FormRequest
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
            'persons' => ['required', 'array'],
            'persons.*' => ['required', 'string', 'exists:temp.persons,person'],
        ];
    }
}
