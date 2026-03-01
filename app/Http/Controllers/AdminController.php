<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Colocation;
use App\Models\Depense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Affiche le dashboard d'administration
     */
    public function dashboard()
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()->is_admin) {
            abort(403, 'Accès refusé. Vous n\'êtes pas administrateur.');
        }

        // Statistiques globales
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_banned', false)->count(),
            'banned_users' => User::where('is_banned', true)->count(),
            'admin_count' => User::where('is_admin', true)->count(),
            
            'total_colocations' => Colocation::count(),
            'active_colocations' => Colocation::where('status_colocation', 'active')->count(),
            'inactive_colocations' => Colocation::where('status_colocation', 'inactive')->count(),
            
            'total_expenses' => Depense::count(),
            'total_spent' => Depense::sum('montant'),
            'avg_expense' => Depense::avg('montant') ?? 0,
        ];

        // Utilisateurs avec options de tri/filtrage
        $query = User::query();
        
        if (request('search')) {
            $search = request('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        if (request('filter') === 'banned') {
            $query->where('is_banned', true);
        } elseif (request('filter') === 'active') {
            $query->where('is_banned', false);
        } elseif (request('filter') === 'admin') {
            $query->where('is_admin', true);
        }

        $users = $query->paginate(15);

        return view('admin.dashboard', compact('stats', 'users'));
    }

    /**
     * Bannir un utilisateur
     */
    public function ban(User $user)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()->is_admin) {
            abort(403, 'Accès refusé. Vous n\'êtes pas administrateur.');
        }

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas vous bannir vous-même.');
        }

        $user->update(['is_banned' => true]);

        // Supprimer l'utilisateur de toutes ses colocations
        $user->colocations()->detach();

        return back()->with('success', "{$user->name} a été banni(e) du site.");
    }

    /**
     * Débannir un utilisateur
     */
    public function unban(User $user)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()->is_admin) {
            abort(403, 'Accès refusé. Vous n\'êtes pas administrateur.');
        }

        $user->update(['is_banned' => false]);

        return back()->with('success', "{$user->name} a été débanni(e).");
    }

    /**
     * Promouvoir un utilisateur en admin
     */
    public function makeAdmin(User $user)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()->is_admin) {
            abort(403, 'Accès refusé. Vous n\'êtes pas administrateur.');
        }

        $user->update(['is_admin' => true]);

        return back()->with('success', "{$user->name} est maintenant administrateur.");
    }

    /**
     * Retirer les droits admin à un utilisateur
     */
    public function removeAdmin(User $user)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()->is_admin) {
            abort(403, 'Accès refusé. Vous n\'êtes pas administrateur.');
        }

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas vous retirer les droits admin.');
        }

        $user->update(['is_admin' => false]);

        return back()->with('success', "Les droits administrateur de {$user->name} ont été retirés.");
    }
}
