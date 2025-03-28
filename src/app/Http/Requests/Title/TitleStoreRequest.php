<?php

namespace App\Http\Requests\Title;

use App\Enums\AgeLimiter;
use App\Enums\TitleStatus;
use App\Enums\TranslateStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TitleStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Request $request): bool
    {
        return $request->user() != null ? true : false;
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
            'name' => ['required', 'string', 'unique:temp.titles,ru_name'],
            'altName' => ['nullable', 'string'],
            // 'cover' => [
            //     'path' => ['required', 'url:http,https'],
            //     'extension' => ['required', 'string']
            // ],
            'path' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'type' => ['nullable', 'string'],
            'titleStatus' => ['nullable', 'integer', Rule::enum(TitleStatus::class)],
            'translateStatus' => ['nullable', 'integer', Rule::enum(TranslateStatus::class)],
            'releaseFormat' => ['nullable', 'string'],
            'releaseYear' => ['nullable', 'integer', 'digits:4', 'between:1900,' . date('Y')],
            'ageLimiter' => ['nullable', 'integer', Rule::enum(AgeLimiter::class)],
            'otherNames' => ['nullable', 'string'],
            'contacts' => ['nullable', 'array'],
            'country' => ['nullable', 'string'],
        ];
    }
}
