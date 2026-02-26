<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colocation extends Model
{
    protected $fillable = [
        'nom_colocation',
        'discription',
        'status_colocation',
        'date_colocation',
        'owner_id'
    ];


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

    /*
    |--------------------------------------------------------------------------
    | HELPERS (Méthodes utiles)
    |--------------------------------------------------------------------------
    */

    /**
     * Récupère le nombre de membres dans cette colocation
     */
    public function getMembersCount()
    {
        return $this->users()->count();
    }

    /**
     * Récupère le total des dépenses du mois courant
     */
    public function getMonthlyExpensesTotal()
    {
        return $this->depenses()
            ->whereMonth('created_at', now()->month)
            ->sum('montant');
    }

    /**
     * Vérifier si c'est une colocation active
     */
    public function isActive()
    {
        return $this->status_colocation === 'active';
    }

    /**
     * Obtenir les dépenses récentes (dernières 5)
     */
    public function getRecentExpenses()
    {
        return $this->depenses()
            ->latest()
            ->limit(5)
            ->with('user', 'categorie')
            ->get();
    }
}
