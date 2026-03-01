<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Depense;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function create(Colocation $colocation)
    {
        if (!$colocation->users->contains(Auth::id())) {
            abort(403);
        }

        $categories = $colocation->categories;

        return view('expenses.create', compact('colocation', 'categories'));
    }

    public function store(Request $request, Colocation $colocation)
    {
        if (!$colocation->users->contains(Auth::id())) {
            abort(403);
        }

        $request->validate([
            'titre_depense' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0.01',
            'categorie_id' => 'required|exists:categories,id'
        ]);

        $depense = Depense::create([
            'titre_depense' => $request->titre_depense,
            'montant' => $request->montant,
            'user_id' => Auth::id(),
            'colocation_id' => $colocation->id,
            'categorie_id' => $request->categorie_id
        ]);

        $members = $colocation->users;

        foreach ($members as $member) {
            $depense->paiements()->create([
                'user_id' => $member->id,
                'status_paiement' => $member->id === Auth::id() ? 1 : 0
            ]);
        }

        return redirect()
            ->route('colocations.show', $colocation)
            ->with('success', 'Dépense ajoutée avec succès.');
    }

    public function destroy(Depense $expense)
    {
        $colocation = $expense->colocation;

        if (!$colocation) {
            abort(404);
        }

        if (
            Auth::id() !== $expense->user_id &&
            Auth::id() !== $colocation->owner_id
        ) {
            abort(403);
        }

        $expense->delete();

        return back()->with('success', 'Dépense supprimée.');
    }

    public function markAsPaid(Paiement $paiement)
    {
        $depense = $paiement->depense;
        $colocation = $depense->colocation;

        if (
            Auth::id() !== $paiement->user_id &&
            Auth::id() !== $colocation->owner_id
        ) {
            abort(403);
        }

        $paiement->update(['status_paiement' => 1]);

        return back()->with('success', 'Paiement marqué comme payé.');
    }

    /**
     * Marque tous les paiements d'une dette comme payés
     */
    public function markDebtAsPaid(Colocation $colocation, $fromId, $toId)
    {
        $fromId = (int)$fromId;
        $toId = (int)$toId;
        $userId = Auth::id();

        if (!$colocation->users->contains($userId)) {
            abort(403, 'Vous n\'êtes pas membre de cette colocation.');
        }

        if ($userId !== $fromId && $userId !== $colocation->owner_id) {
            abort(403, 'Vous n\'avez pas la permission de marquer cette dette. Seul celui qui doit et le owner peuvent le faire.');
        }

        DB::table('paiements')
            ->join('depenses', 'paiements.depense_id', '=', 'depenses.id')
            ->where('depenses.colocation_id', $colocation->id)
            ->where('depenses.user_id', $toId)
            ->where('paiements.user_id', $fromId)
            ->where('paiements.status_paiement', 0)
            ->update(['paiements.status_paiement' => 1]);

        return back()->with('success', 'Dettes marquées comme payées.');
    }
}