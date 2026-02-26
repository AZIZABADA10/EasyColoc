<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre_categorie' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'titre_categorie.required' => 'Le nom de la catégorie est obligatoire.',
        ];
    }
}
