<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
    protected $fillable = [
        'titre_depense',
        'montant',
        'user_id',
        'colocation_id',
        'categorie_id'
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Affiche le montant formaté (ex: "25.50 €")
     */
    public function getMontantFormatted()
    {
        return number_format($this->montant, 2, ',', ' ') . ' €';
    }
}
