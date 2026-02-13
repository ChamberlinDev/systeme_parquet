<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{

   protected $primaryKey = 'id_dossier';
    protected $fillable = [
        'num_dossier',
        'date_enregistrement',
        'type_affaire',
        'statut',
        'id_parquet',
        'id_greffier',
        'id_registre'
    ];

    public function registre()
    {
        return $this->belongsTo(Registre::class, 'id_registre');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'id_dossier');
    }

    public function parties()
    {
        return $this->hasMany(Partie::class, 'id_dossier');
    }

    public function greffier()
    {
        return $this->belongsTo(Greffier::class, 'id_greffier');
    }
}
