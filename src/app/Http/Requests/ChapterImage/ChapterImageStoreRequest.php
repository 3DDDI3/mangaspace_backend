<?php

namespace App\Http\Requests\ChapterImage;

use Illuminate\Foundation\Http\FormRequest;

class ChapterImageStoreRequest extends FormRequest
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
            'images.*' => ['required', 'array'],
            'images.*.path' => ['required', 'string'],
            'images.*.extension' => ['required', 'string'],
        ];
    }
}
