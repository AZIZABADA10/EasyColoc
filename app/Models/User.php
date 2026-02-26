<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_banned',
        'reputation'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */


    public function ownedColocations()
    {
        return $this->hasMany(Colocation::class, 'owner_id');
    }


    public function colocations()
    {
        return $this->belongsToMany(Colocation::class)
                    ->withPivot('role')
                    ->withTimestamps();
    }


    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }


    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS (Méthodes utiles)
    |--------------------------------------------------------------------------
    */

    /**
     * Récupère la colocation ACTIVE de cet utilisateur
     * (il ne peut avoir qu'une seule à la fois)
     */
    public function getActiveColocation()
    {
        return $this->colocations()
            ->where('status_colocation', 'active')
            ->first();
    }

    /**
     * Vérifie si l'utilisateur a une colocation active
     */
    public function hasActiveColocation()
    {
        return $this->getActiveColocation() !== null;
    }

    /**
     * Vérifie si l'utilisateur est propriétaire d'une colocation
     */
    public function ownsColocation(Colocation $colocation)
    {
        return $this->id === $colocation->owner_id;
    }

    /**
     * Récupère le rôle de l'utilisateur dans une colocation
     * Retourne 'owner' ou 'member'
     */
    public function getRoleInColocation(Colocation $colocation)
    {
        return $this->colocations()
            ->where('colocation_id', $colocation->id)
            ->first()?->pivot->role;
    }
}
