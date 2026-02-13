<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Greffier extends Model
{
    //
    protected $fillable = [
        'id_greffier',
        'nom',
        'prenom',
        'fonction'
    ];


    public function dossiers()
    {
        return $this->hasMany(Dossier::class, 'id_greffier');
    }
}
