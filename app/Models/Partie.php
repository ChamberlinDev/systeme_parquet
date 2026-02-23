<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partie extends Model
{
    //
    
    protected $fillable = [
        'nom',
        'prenom',
        'contact',
        'role'
    ];

    public function dossier()
    {
        return $this->belongsTo(Dossier::class);
    }
}
