<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Colocation extends Model
{
    protected $fillable = [
        'nom_colocation',
        'discription',
        'status_colocation',
        'date_colocation',
        'owner_id'
    ];

    /* =========================
       RELATIONS
    ==========================*/

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }

    public function categories()
    {
        return $this->hasMany(Categorie::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    /* =========================
       MÉTHODES UTILES
    ==========================*/

    public function getMembersCount()
    {
        return $this->users()->count();
    }

    public function getMonthlyExpensesTotal()
    {
        return $this->depenses()
            ->whereMonth('created_at', now()->month)
            ->sum('montant');
    }

    public function isActive()
    {
        return $this->status_colocation === 'active';
    }

    public function getRecentExpenses()
    {
        return $this->depenses()
            ->latest()
            ->limit(5)
            ->with('user', 'categorie')
            ->get();
    }

    /* =========================
       CALCUL SOLDE
    ==========================*/

    public function calculateBalances()
    {
        $members = $this->users;
        $depenses = $this->depenses;

        $total = $depenses->sum('montant');
        $count = $members->count();

        if ($count === 0) {
            return [];
        }

        $part = $total / $count;

        $balances = [];

        foreach ($members as $member) {

            $paid = $depenses
                ->where('user_id', $member->id)
                ->sum('montant');

            $balances[] = [
                'user' => $member,
                'paid' => $paid,
                'should_pay' => $part,
                'balance' => $paid - $part,
            ];
        }

        return $balances;
    }

    /**
     * Récupère toutes les dettes non payées de la colocation
     * Groupées par (payeur -> créancier)
     */
    public function getDebts()
    {
        $debts = collect();
        $membersCount = $this->users->count();

        if ($membersCount === 0) {
            return $debts;
        }

        // Récupère tous les paiements non payés
        $unpaidPayments = DB::table('paiements')
            ->join('depenses', 'paiements.depense_id', '=', 'depenses.id')
            ->where('depenses.colocation_id', $this->id)
            ->where('paiements.status_paiement', 0)
            ->select(
                'paiements.id as payment_id',
                'paiements.user_id',
                'paiements.status_paiement',
                'depenses.user_id as payeur_id',
                'depenses.montant',
                'depenses.id as depense_id'
            )
            ->get();

        $montantParMembre = collect();
        $paymentIds = collect();

        foreach ($unpaidPayments as $payment) {
            $montantDu = $payment->montant / $membersCount;
            
            // user_id = celui qui doit payer
            // payeur_id = celui qui a payé
            $key = $payment->user_id . '_' . $payment->payeur_id;

            if ($montantParMembre->has($key)) {
                $montantParMembre[$key]['montant'] += $montantDu;
                $paymentIds[$key][] = $payment->payment_id;
            } else {
                $montantParMembre[$key] = [
                    'from_id' => $payment->user_id,
                    'to_id' => $payment->payeur_id,
                    'montant' => $montantDu,
                ];
                $paymentIds[$key] = [$payment->payment_id];
            }
        }

        // Transformer en collection avec les objets User
        foreach ($montantParMembre as $key => $debt) {
            $debts[] = [
                'from' => User::find($debt['from_id']),
                'to' => User::find($debt['to_id']),
                'from_id' => $debt['from_id'],
                'to_id' => $debt['to_id'],
                'montant' => $debt['montant'],
                'payment_ids' => $paymentIds[$key],
            ];
        }

        return $debts;
    }

    /**
     * Vérifier s'il existe des dettes non réglées dans la colocation
     * Utilisé pour empêcher l'annulation d'une colocation avec dettes
     */
    public function hasUnpaidDebts()
    {
        return DB::table('paiements')
            ->join('depenses', 'paiements.depense_id', '=', 'depenses.id')
            ->where('depenses.colocation_id', $this->id)
            ->where('paiements.status_paiement', 0)
            ->exists();
    }

    /**
     * Récupérer tous les paiements non payés d'un utilisateur DANS CETTE colocation
     * Utilisé pour vérifier si un member peut quitter
     */
    public function getUnpaidDebtsForUser($userId)
    {
        return DB::table('paiements')
            ->join('depenses', 'paiements.depense_id', '=', 'depenses.id')
            ->where('depenses.colocation_id', $this->id)
            ->where('paiements.user_id', $userId)
            ->where('paiements.status_paiement', 0)
            ->get();
    }
}