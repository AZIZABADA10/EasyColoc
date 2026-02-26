<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreColocationRequest extends FormRequest
{
    /**
     * Qui a le droit d'utiliser ce formulaire ?
     * Si authorize() retourne false → erreur 403
     */
    public function authorize(): bool
    {
        // Seuls les utilisateurs authentifiés peuvent créer une colocation
        return true;
    }

    /**
     * Quelles sont les règles de validation ?
     */
    public function rules(): array
    {
        return [
            'nom_colocation' => ['required', 'string', 'max:255'],
            'discription' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Messages d'erreur personnalisés (optionnel)
     */
    public function messages(): array
    {
        return [
            'nom_colocation.required' => 'Le nom de la colocation est obligatoire.',
            'nom_colocation.max' => 'Le nom ne doit pas dépasser 255 caractères.',
        ];
    }
}
