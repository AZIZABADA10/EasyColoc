<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Depense;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
 
    public function index(Request $request)
    {
        $user = Auth::user();

        $activeColocation = $user->getActiveColocation();

        $recentExpenses = $activeColocation 
            ? $activeColocation->getRecentExpenses() 
            : collect();

        $stats = [
            'reputation' => $user->reputation ?? 0,
            'total_colocations' => $user->colocations()->count(),
            'owned_colocations' => $user->ownedColocations()->count(),
            'monthly_expenses' => $activeColocation 
                ? $activeColocation->getMonthlyExpensesTotal() 
                : 0,
        ];

        return view('dashboard.index', [
            'user' => $user,
            'activeColocation' => $activeColocation,
            'recentExpenses' => $recentExpenses,
            'stats' => $stats,
        ]);
    }
}
