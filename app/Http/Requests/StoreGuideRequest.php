<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'creator_email' => 'nullable|email|max:255',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Le titre du guide est obligatoire.',
            'title.max' => 'Le titre ne doit pas dépasser 255 caractères.',
            'creator_email.email' => 'L\'adresse email n\'est pas valide.',
        ];
    }
}
