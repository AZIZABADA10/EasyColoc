<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Colocation;
use App\Http\Requests\StoreCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function create(Colocation $colocation)
    {
        if ($colocation->owner_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Seul le propriétaire peut ajouter des catégories.');
        }

        return view('categories.create', compact('colocation'));
    }

    public function store(StoreCategoryRequest $request, Colocation $colocation)
    {
        if ($colocation->owner_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Seul le propriétaire peut ajouter des catégories.');
        }

        $data = $request->validated();

        Categorie::create([
            'titre_categorie' => $data['titre_categorie'],
            'colocation_id' => $colocation->id,
        ]);

        return redirect()
            ->route('colocations.show', $colocation)
            ->with('success', 'Catégorie ajoutée avec succès !');
    }

    public function edit(Categorie $categorie)
    {
        $colocation = $categorie->colocation;

        if ($colocation->owner_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Seul le propriétaire peut modifier des catégories.');
        }

        return view('categories.edit', compact('categorie', 'colocation'));
    }

    public function update(Request $request, Categorie $categorie)
    {
        $colocation = $categorie->colocation;

        if ($colocation->owner_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Seul le propriétaire peut modifier des catégories.');
        }

        $validated = $request->validate([
            'titre_categorie' => 'required|string|max:255',
        ]);

        $categorie->update($validated);

        return redirect()
            ->route('colocations.show', $colocation)
            ->with('success', 'Catégorie modifiée avec succès !');
    }

    public function destroy(Categorie $categorie)
    {
        $colocation = $categorie->colocation;

        if ($colocation->owner_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Seul le propriétaire peut supprimer des catégories.');
        }

        if ($categorie->depenses()->exists()) {
            return back()->with('error', 'Impossible de supprimer cette catégorie. Elle est utilisée par des dépenses.');
        }

        $categorie->delete();

        return redirect()
            ->route('colocations.show', $colocation)
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}

