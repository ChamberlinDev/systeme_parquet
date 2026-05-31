<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DossierHistorique extends Model
{
    protected $table = 'dossier_historique';

    protected $fillable = [
        'id_dossier',
        'user_id',
        'action',
        'detail',
    ];

    public function dossier()
    {
        return $this->belongsTo(Dossier::class, 'id_dossier', 'id_dossier');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
