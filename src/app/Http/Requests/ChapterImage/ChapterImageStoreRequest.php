<?php

namespace App\Http\Requests\ChapterImage;

use App\Enums\PersonType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'translator.name' => ['nullable', 'string'],
            'translator.type' => ['nullable', Rule::enum(PersonType::class)],
            'translator.description' => ['nullable', 'string'],
            'translator.altName' => ['nullable', 'string'],
            'translator.images' => [
                'path' => ['nullable', 'url:http.https'],
                'extension' => ['nullable', 'string'],
            ],

            'extensions' => ['nullable', 'string'],
            'id' => ['nullable', 'string'],
            'name' => ['nullable', 'string'],
            'type' => ['nullable', Rule::in(['image/webp', 'image/png', 'image/jpeg', 'image/jpg'])],
            'file' => ['nullable', 'file'],
        ];
    }
}
