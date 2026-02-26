<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = [
        'depense_id',
        'user_id',
        'status_paiement'
    ];



    public function depense()
    {
        return $this->belongsTo(Depense::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}