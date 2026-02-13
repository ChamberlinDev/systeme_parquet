<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    //
    protected $fillable = [
        'id_dossier',
        'nom',
        'chemin_fichier', // stocke le path du fichier
        'type_document'   // ex: plainte, décision, audience, etc.
    ];

    public function dossier()
    {
        return $this->belongsTo(Dossier::class, 'id_dossier');
    }
}
