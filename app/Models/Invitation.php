<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = [
        'email',
        'token',
        'statut',
        'colocation_id'
    ];



    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }
}
