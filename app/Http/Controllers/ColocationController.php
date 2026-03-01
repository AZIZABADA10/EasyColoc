<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Http\Requests\StoreColocationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ColocationController extends Controller
{
    public function index()
    {
        $colocations = Auth::user()->colocations;

        return view('colocations.index', compact('colocations'));
    }

    public function create()
    {
        return view('colocations.create');
    }

    public function store(StoreColocationRequest $request)
    {
        $data = $request->validated();

        $colocation = Colocation::create([
            'nom_colocation' => $data['nom_colocation'],
            'discription' => $data['discription'] ?? null,
            'owner_id' => Auth::id(),
            'status_colocation' => 'active',
        ]);

        $colocation->users()->attach(Auth::id(), [
            'role' => 'owner'
        ]);

        return redirect()
            ->route('colocations.show', $colocation)
            ->with('success', 'Colocation créée avec succès !');
    }

    public function show(Colocation $colocation)
    {
        $colocation->load('users', 'categories', 'depenses.user', 'depenses.categorie', 'depenses.paiements.user');
        $debts = $colocation->getDebts();

        return view('colocations.show', compact('colocation', 'debts'));
    }

    /**
     * Afficher le formulaire d'édition d'une colocation
     */
    public function edit(Colocation $colocation)
    {
        if ($colocation->owner_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Seul le propriétaire peut modifier cette colocation.');
        }

        return view('colocations.edit', compact('colocation'));
    }

    /**
     * Mettre à jour une colocation
     */
    public function update(Request $request, Colocation $colocation)
    {
        if ($colocation->owner_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Seul le propriétaire peut modifier cette colocation.');
        }

        $validated = $request->validate([
            'nom_colocation' => 'required|string|max:255',
            'discription' => 'nullable|string|max:1000',
        ]);

        $colocation->update($validated);

        return redirect()
            ->route('colocations.show', $colocation)
            ->with('success', 'Colocation modifiée avec succès.');
    }

    /**
     * Supprimer une colocation (ANNULER)
     * 
     * Règles :
     * - Seul le owner ou admin peut annuler
     * - Vérifier qu'il n'existe AUCUNE dette non réglée
     * - Si dettes → empêcher avec message erreur
     * - Sinon supprimer complètement
     */
    public function destroy(Colocation $colocation)
    {
        if ($colocation->owner_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Vous n\'avez pas le droit de supprimer cette colocation.');
        }

        // Vérifier s'il existe des dettes non réglées
        if ($colocation->hasUnpaidDebts()) {
            return back()->with('error', 'Impossible d\'annuler la colocation. Il existe encore des dettes non réglées. Veuillez d\'abord régler toutes les dettes.');
        }

        $colocation->users()->detach();

        $colocation->delete();

        return redirect()
            ->route('colocations.index')
            ->with('success', 'Colocation annulée et supprimée.');
    }

 
    public function leave(Colocation $colocation)
    {
        $memberId = Auth::id();

        // Vérification : le owner ne peut pas quitter
        if ($memberId == $colocation->owner_id) {
            return back()->with('error', 'Le propriétaire ne peut pas quitter la colocation.');
        }

        // Vérification : le member doit faire partie de la colocation
        if (!$colocation->users->contains($memberId)) {
            abort(403, 'Vous n\'êtes pas membre de cette colocation.');
        }

        try {
            // Vérifier s'il a des dettes non réglées
            $unpaidDebts = $colocation->getUnpaidDebtsForUser($memberId);

            if ($unpaidDebts->count() > 0) {
                return back()->with('error', 'Vous avez des dettes non réglées. Impossible de quitter la colocation pour le moment.');
            }

            DB::transaction(function () use ($colocation, $memberId) {
                // Le member n'a pas de dettes = BONUS +1 réputation (fidélité)
                User::find($memberId)->increment('reputation');

                // Retirer le member de la colocation
                $colocation->users()->detach($memberId);
            });

            return redirect()
                ->route('dashboard')
                ->with('success', 'Vous avez quitté la colocation. +1 réputation pour votre fidélité !');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur s\'est produite. Veuillez réessayer.');
        }
    }

    public function removeMember(Colocation $colocation, User $user)
    {
        // Vérification : seul le owner peut retirer des membres
        if (Auth::id() != $colocation->owner_id) {
            abort(403, 'Seul le propriétaire peut retirer des membres.');
        }

        // Vérification : on ne peut pas retirer le owner
        if ($user->id == $colocation->owner_id) {
            return back()->with('error', 'Le propriétaire ne peut pas être retiré.');
        }

        // Vérification : le membre doit faire partie de la colocation
        if (!$colocation->users->contains($user->id)) {
            return back()->with('error', 'Ce membre n\'appartient pas à cette colocation.');
        }

        try {
            // Compter les dettes AVANT la transaction
            $unpaidPaymentCount = DB::table('paiements')
                ->join('depenses', 'paiements.depense_id', '=', 'depenses.id')
                ->where('depenses.colocation_id', $colocation->id)
                ->where('paiements.user_id', $user->id)
                ->where('paiements.status_paiement', 0)
                ->count();

            DB::transaction(function () use ($colocation, $user) {
                // 1. Récupérer tous les paiements non payés du user dans CETTE colocation
                $unpaidPaymentIds = DB::table('paiements')
                    ->join('depenses', 'paiements.depense_id', '=', 'depenses.id')
                    ->where('depenses.colocation_id', $colocation->id)
                    ->where('paiements.user_id', $user->id)
                    ->where('paiements.status_paiement', 0)
                    ->pluck('paiements.id');

                // 2. CAS IMPORTANT : Si le member a des dettes
                if ($unpaidPaymentIds->count() > 0) {
                    // 2a. IMPUTER LES DETTES AU OWNER
                    // Changer le user_id du paiement → owner absorbe la dette
                    DB::table('paiements')
                        ->whereIn('id', $unpaidPaymentIds)
                        ->update(['user_id' => $colocation->owner_id]);
                    // NOTE : Pas de marquer comme payé, les dettes deviennent celles du owner
                }

                // 3. Retirer le member de la colocation
                $colocation->users()->detach($user->id);
            });

            $message = $unpaidPaymentCount > 0 
                ? "Membre retiré. {$unpaidPaymentCount} dette(s) imputée(s) à vous." 
                : 'Membre retiré.';

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur s\'est produite. Veuillez réessayer.');
        }
    }

    public function balance(Colocation $colocation)
    {
        if (!$colocation->users->contains(auth()->id())) {
            abort(403);
        }

        $balances = $colocation->calculateBalances();

        return view('colocations.balance', compact('colocation', 'balances'));
    }
}