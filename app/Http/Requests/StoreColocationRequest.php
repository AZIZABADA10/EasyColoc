<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreColocationRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'nom_colocation' => ['required', 'string', 'max:255'],
            'discription' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom_colocation.required' => 'Le nom de la colocation est obligatoire.',
            'nom_colocation.max' => 'Le nom ne doit pas dépasser 255 caractères.',
        ];
    }
}
