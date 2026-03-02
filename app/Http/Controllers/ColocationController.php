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
        $userColocations = Auth::user()->colocations()->count();
        $hasColocation = $userColocations > 0;
        
        return view('colocations.create', compact('hasColocation'));
    }

    public function store(StoreColocationRequest $request)
    {
         $userColocations = Auth::user()->colocations()->count();
        if ($userColocations > 0) {
            return redirect()
                ->route('colocations.create')
                ->with('error', 'Vous êtes déjà membre d\'une colocation. Vous ne pouvez pas en créer une nouvelle.');
        }

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


    public function edit(Colocation $colocation)
    {
        if ($colocation->owner_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Seul le propriétaire peut modifier cette colocation.');
        }

        return view('colocations.edit', compact('colocation'));
    }

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


    public function destroy(Colocation $colocation)
    {
        if ($colocation->owner_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Vous n\'avez pas le droit de supprimer cette colocation.');
        }


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

        if ($memberId == $colocation->owner_id) {
            return back()->with('error', 'Le propriétaire ne peut pas quitter la colocation.');
        }

        if (!$colocation->users->contains($memberId)) {
            abort(403, 'Vous n\'êtes pas membre de cette colocation.');
        }

        try {
             $unpaidPaymentCount = DB::table('paiements')
                ->join('depenses', 'paiements.depense_id', '=', 'depenses.id')
                ->where('depenses.colocation_id', $colocation->id)
                ->where('paiements.user_id', $memberId)
                ->where('paiements.status_paiement', 0)
                ->count();

            $hasUnpaidDebts = $unpaidPaymentCount > 0;

            DB::transaction(function () use ($colocation, $memberId, $hasUnpaidDebts, $unpaidPaymentCount) {
                 if ($hasUnpaidDebts) {
                    $unpaidPaymentIds = DB::table('paiements')
                        ->join('depenses', 'paiements.depense_id', '=', 'depenses.id')
                        ->where('depenses.colocation_id', $colocation->id)
                        ->where('paiements.user_id', $memberId)
                        ->where('paiements.status_paiement', 0)
                        ->pluck('paiements.id');

                    if ($unpaidPaymentIds->count() > 0) {
                        DB::table('paiements')
                            ->whereIn('id', $unpaidPaymentIds)
                            ->update(['user_id' => $colocation->owner_id]);
                    }
                }

                 $reputationChange = $hasUnpaidDebts ? -1 : 1;
                User::find($memberId)->increment('reputation', $reputationChange);

                 $colocation->users()->detach($memberId);
            });

            $message = $hasUnpaidDebts 
                ? "Vous avez quitté la colocation. {$unpaidPaymentCount} dette(s) transférée(s) au propriétaire. -1 réputation." 
                : 'Vous avez quitté la colocation. +1 réputation pour votre fidélité !';

            return redirect()
                ->route('dashboard')
                ->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur s\'est produite. Veuillez réessayer.');
        }
    }
    public function removeMember(Colocation $colocation, User $user)
    {
        if (Auth::id() != $colocation->owner_id) {
            abort(403, 'Seul le propriétaire peut retirer des membres.');
        }

        if ($user->id == $colocation->owner_id) {
            return back()->with('error', 'Le propriétaire ne peut pas être retiré.');
        }

        if (!$colocation->users->contains($user->id)) {
            return back()->with('error', 'Ce membre n\'appartient pas à cette colocation.');
        }

        try {
            $unpaidPaymentCount = DB::table('paiements')
                ->join('depenses', 'paiements.depense_id', '=', 'depenses.id')
                ->where('depenses.colocation_id', $colocation->id)
                ->where('paiements.user_id', $user->id)
                ->where('paiements.status_paiement', 0)
                ->count();

            $hasUnpaidDebts = $unpaidPaymentCount > 0;

            DB::transaction(function () use ($colocation, $user, $unpaidPaymentCount, $hasUnpaidDebts) {
                 $reputationChange = $hasUnpaidDebts ? -1 : 1;
                $user->increment('reputation', $reputationChange);

                $unpaidPaymentIds = DB::table('paiements')
                    ->join('depenses', 'paiements.depense_id', '=', 'depenses.id')
                    ->where('depenses.colocation_id', $colocation->id)
                    ->where('paiements.user_id', $user->id)
                    ->where('paiements.status_paiement', 0)
                    ->pluck('paiements.id');

                if ($unpaidPaymentIds->count() > 0) {
                    DB::table('paiements')
                        ->whereIn('id', $unpaidPaymentIds)
                        ->update(['user_id' => $colocation->owner_id]);
                }

                $colocation->users()->detach($user->id);
            });

            $reputationInfo = $hasUnpaidDebts ? '-1 réputation' : '+1 réputation';
            $message = $unpaidPaymentCount > 0 
                ? "{$user->name} retiré. {$unpaidPaymentCount} dette(s) imputée(s) à vous. ({$reputationInfo})" 
                : "{$user->name} retiré. ({$reputationInfo})";

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