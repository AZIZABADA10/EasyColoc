<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Colocation;
use App\Http\Requests\StoreCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    
    public function create(Colocation $colocation)
    {

        if ($colocation->owner_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }

        return view('categories.create', compact('colocation'));
    }
 
    public function store(StoreCategoryRequest $request, Colocation $colocation)
    {
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

 
    public function destroy(Categorie $categorie)
    {
        $colocation = $categorie->colocation;


        if ($colocation->owner_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }

        $categorie->delete();

        return redirect()
            ->route('colocations.show', $colocation)
            ->with('success', 'Catégorie supprimée.');
    }
}
