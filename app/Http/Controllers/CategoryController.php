<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Colocation;
use App\Http\Requests\StoreCategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Affiche le formulaire de création d'une catégorie
     */
    public function create(Colocation $colocation)
    {
        // Vérifier que l'utilisateur a le droit de faire ça
        // (pour le moment : doit être le owner ou admin)
        if ($colocation->owner_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }

        return view('categories.create', compact('colocation'));
    }

    /**
     * Créer une catégorie
     */
    public function store(StoreCategoryRequest $request, Colocation $colocation)
    {
        // Vérification d'autorisation
        if ($colocation->owner_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }

        $data = $request->validated();

        Categorie::create([
            'titre_categorie' => $data['titre_categorie'],
            'colocation_id' => $colocation->id,
        ]);

        return redirect()
            ->route('colocations.show', $colocation)
            ->with('success', 'Catégorie créée !');
    }

    /**
     * Supprimer une catégorie
     */
    public function destroy(Categorie $categorie)
    {
        $colocation = $categorie->colocation;

        // Vérification d'autorisation
        if ($colocation->owner_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }

        $categorie->delete();

        return redirect()
            ->route('colocations.show', $colocation)
            ->with('success', 'Catégorie supprimée.');
    }
}
