<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
    protected $primaryKey = 'id_dossier';
    protected $fillable = [
        'numero_rp',
        'numero_registre',
        'id_registre',
        'nature_infraction',
        'date_demande',
        'parquet_id',
        'statut',
        'id_greffier',
    ];

    // public function dossiers()
    // {
    //     return $this->hasMany(Dossier::class);
    // }

    public function parquet()
    {
        return $this->belongsTo(Parquet::class, 'parquet_id');
    }
    public function registre()
    {
        return $this->belongsTo(Registre::class, 'id_registre', 'id_registre');
    }

    public function files()
    {
        return $this->hasMany(Dossier_files::class, 'id_dossier', 'id_dossier');
    }

    public function parties()
    {
        return $this->hasMany(Partie::class, 'id_dossier', 'id_dossier');
    }
    public function greffier()
    {
        return $this->belongsTo(User::class, 'id_greffier');
    }
}
