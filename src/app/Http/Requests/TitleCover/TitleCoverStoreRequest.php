<?php

namespace App\Http\Requests\TitleCover;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class TitleCoverStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Request $request): bool
    {
        return $request->user() == null ? false : true;
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
            '*.path' => ['required', 'string'],
            '*.extension' => ['required', 'string']
        ];
    }
}
