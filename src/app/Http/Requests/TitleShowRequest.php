<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'ru_name' => ['nullable', 'exists:temp.titles,ru_name'],
            'eng_name' => ['nullable', 'exists:temp.titles,eng_name'],
            'slug' => ['nullable', 'string'],
        ];
    }
}
