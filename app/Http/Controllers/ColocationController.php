<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Http\Requests\StoreColocationRequest;
use Illuminate\Http\Request;

class ColocationController extends Controller
{
    /**
     * Affiche la liste des colocations de l'utilisateur
     */
    public function index()
    {
        // Récupérer toutes les colocations de l'utilisateur
        $colocations = auth()->user()->colocations;

        return view('colocations.index', compact('colocations'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        return view('colocations.create');
    }

    /**
     * Sauvegarde une nouvelle colocation dans la base de données
     * 
     * $request est un StoreColocationRequest qui valide déjà les données
     * (pas besoin de faire validate() ici, c'est fait automatiquement)
     */
    public function store(StoreColocationRequest $request)
    {
        // $request->validated() retourne SEULEMENT les données validées
        $data = $request->validated();

        // Créer la colocation avec owner_id
        $colocation = Colocation::create([
            'nom_colocation' => $data['nom_colocation'],
            'discription' => $data['discription'] ?? null,
            'owner_id' => auth()->id(),
            'status_colocation' => 'active',
        ]);

        // Attacher le créateur comme 'owner' dans la table pivot
        $colocation->users()->attach(auth()->id(), [
            'role' => 'owner'
        ]);

        return redirect()
            ->route('colocations.show', $colocation)
            ->with('success', 'Colocation créée avec succès !');
    }

    /**
     * Affiche les détails d'une colocation
     */
    public function show(Colocation $colocation)
    {
        // Charger les relations pour ne pas avoir de N+1 query
        $colocation->load('users', 'categories', 'depenses.user');

        return view('colocations.show', compact('colocation'));
    }

    /**
     * Supprime une colocation
     * 
     * TODO : À l'avenir, ajouter une vérification :
     * - Vérifier que c'est le owner ou un admin qui supprime
     * - Ajouter une logique d'autorisation
     */
    public function destroy(Colocation $colocation)
    {
        // Vérifier que c'est le owner
        if ($colocation->owner_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403, 'Vous n\'avez pas le droit de supprimer cette colocation.');
        }

        $colocation->delete();

        return redirect()
            ->route('colocations.index')
            ->with('success', 'Colocation supprimée.');
    }
}