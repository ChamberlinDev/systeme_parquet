<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{

    protected $primaryKey = 'id_dossier';
    protected $fillable = [
        'id_dossier',
        'registre_rp',
        'type_affaire',
        'date_demande',
        'statut',
        'id_greffier',
        'id_parquet',
    ];




    public function greffier()
    {
        return $this->belongsTo(Greffier::class, 'id_greffier');
    }
    public function files()
    {
        return $this->hasMany(Dossier_files::class, 'dossier_id');
    }
    public function parties()
    {
        return $this->hasMany(Partie::class);
    }
}
