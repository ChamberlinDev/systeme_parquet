<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registre extends Model
{
    //
    protected $fillable =
    [
        'code',
        'nom'
    ];

    public function dossiers()
    {
        return $this->hasMany(Dossier::class, 'id_registre');
    }
}
