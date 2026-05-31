<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audience extends Model
{
    protected $primaryKey = 'id_audience';

    protected $fillable = [
        'date_audience',
        'salle',
        'type_audience',
        'role',
    ];

    public function dossiers()
    {
        return $this->belongsToMany(Dossier::class, 'dossier_audience', 'id_audience', 'id_dossier');
    }
}
