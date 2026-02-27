<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Http\Requests\StoreColocationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
         $colocation->load('users', 'categories', 'depenses.user');

        return view('colocations.show', compact('colocation'));
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
}