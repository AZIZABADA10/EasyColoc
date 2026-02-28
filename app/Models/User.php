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

 
    public function getActiveColocation()
    {
        return $this->colocations()
            ->where('status_colocation', 'active')
            ->first();
    }
 
    public function hasActiveColocation()
    {
        return $this->getActiveColocation() !== null;
    }
 
    public function ownsColocation(Colocation $colocation)
    {
        return $this->id === $colocation->owner_id;
    }
 
    public function getRoleInColocation(Colocation $colocation)
    {
        return $this->colocations()
            ->where('colocation_id', $colocation->id)
            ->first()?->pivot->role;
    }
}
