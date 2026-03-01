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

    public function destroy(Colocation $colocation)
    {
        if ($colocation->owner_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Vous n\'avez pas le droit de supprimer cette colocation.');
        }

        $colocation->delete();

        return redirect()
            ->route('colocations.index')
            ->with('success', 'Colocation supprimée.');
    }

 
    public function leave(Colocation $colocation)
    {
        $memberId = auth()->id();

        // Vérification : le owner ne peut pas quitter
        if ($memberId == $colocation->owner_id) {
            return back()->with('error', 'Le propriétaire ne peut pas quitter la colocation.');
        }

        // Vérification : le member doit faire partie de la colocation
        if (!$colocation->users->contains($memberId)) {
            abort(403, 'Vous n\'êtes pas membre de cette colocation.');
        }

        try {
            DB::transaction(function () use ($colocation, $memberId) {
                // 1. Récupérer tous les paiements non payés du member dans CETTE colocation
                $unpaidPaymentIds = DB::table('paiements')
                    ->join('depenses', 'paiements.depense_id', '=', 'depenses.id')
                    ->where('depenses.colocation_id', $colocation->id)
                    ->where('paiements.user_id', $memberId)
                    ->where('paiements.status_paiement', 0)
                    ->pluck('paiements.id');

                // 2. Si le member a des dettes
                if ($unpaidPaymentIds->count() > 0) {
                    // 2a. Réduire sa réputation de 1
                    User::find($memberId)->decrement('reputation');

                    // 2b. Marquer tous ses paiements non payés comme payés
                    DB::table('paiements')
                        ->whereIn('id', $unpaidPaymentIds)
                        ->update(['status_paiement' => 1]);
                }

                // 3. Retirer le member de la colocation
                $colocation->users()->detach($memberId);
            });

            return redirect()
                ->route('dashboard')
                ->with('success', 'Vous avez quitté la colocation. Vos dettes impayées ont été pardonnées.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur s\'est produite. Veuillez réessayer.');
        }
    }

    /**
     * ========================================
     * CAS 2 : Retrait d'un member par le owner
     * ========================================
     * 
     * Logique :
     * - Le owner retire un member
     * - La réputation du member NE change pas
     * - Ses paiements non payés sont marqués comme payés
     * - Le owner prend implicitement en charge la dette
     * 
     * Sécurité : Transaction atomique, vérifications d'autorisation
     */
    public function removeMember(Colocation $colocation, User $user)
    {
        // Vérification : seul le owner peut retirer des membres
        if (auth()->id() != $colocation->owner_id) {
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
            DB::transaction(function () use ($colocation, $user) {
                // 1. Récupérer tous les paiements non payés du user dans CETTE colocation
                $unpaidPaymentIds = DB::table('paiements')
                    ->join('depenses', 'paiements.depense_id', '=', 'depenses.id')
                    ->where('depenses.colocation_id', $colocation->id)
                    ->where('paiements.user_id', $user->id)
                    ->where('paiements.status_paiement', 0)
                    ->pluck('paiements.id');

                // 2. Si le member a des dettes
                if ($unpaidPaymentIds->count() > 0) {
                    // 2a. Marquer ses paiements non payés comme payés
                    // NOTE : La réputation NE change pas (contrairement au leave())
                    DB::table('paiements')
                        ->whereIn('id', $unpaidPaymentIds)
                        ->update(['status_paiement' => 1]);
                }

                // 3. Retirer le member de la colocation
                $colocation->users()->detach($user->id);
            });

            return back()->with('success', 'Membre retiré et ses dettes ont été pardonnées.');
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