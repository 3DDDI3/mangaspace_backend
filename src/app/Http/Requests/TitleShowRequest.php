<?php

namespace App\Http\Requests;

use App\Enums\TitleStatus;
use App\Enums\TranslateStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TitleShowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['nullable', 'integer'],
            'ru_name' => ['nullable', 'string'],
            'eng_name' => ['nullable', 'string'],
            'slug' => ['nullable', 'string'],
            'translateStatus' => ['nullable', Rule::enum(TranslateStatus::class)],
            'titleStatus' => ['nullable', Rule::enum(TitleStatus::class)],
            'category' => ['nullable', 'integer', Rule::exists('temp.categories', 'id')],
        ];
    }
}
