<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Depense;

class DashboardController extends Controller
{
    /**
     * Affiche le dashboard de l'utilisateur
     * 
     * Affiche :
     * - Les infos de l'utilisateur (réputation)
     * - Sa colocation active (s'il en a une)
     * - Les membres de cette colocation
     * - Les dépenses récentes
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Récupérer la colocation ACTIVE de l'utilisateur
        $activeColocation = $user->getActiveColocation();

        // Récupérer les dépenses récentes si une colocation existe
        $recentExpenses = $activeColocation 
            ? $activeColocation->getRecentExpenses() 
            : collect();

        // Récupérer les statistiques simples
        $stats = [
            'reputation' => $user->reputation ?? 0,
            'total_colocations' => $user->colocations()->count(),
            'owned_colocations' => $user->ownedColocations()->count(),
            'monthly_expenses' => $activeColocation 
                ? $activeColocation->getMonthlyExpensesTotal() 
                : 0,
        ];

        // Transmettre à la vue
        return view('dashboard.index', [
            'user' => $user,
            'activeColocation' => $activeColocation,
            'recentExpenses' => $recentExpenses,
            'stats' => $stats,
        ]);
    }
}
