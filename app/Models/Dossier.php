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
        'decision_orientation',
        'motif_orientation',
        'date_orientation',
        'id_procureur',
        'motif_archivage',
        'date_archivage',
        'acte_signe_path',
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

    public function audiences()
    {
        return $this->belongsToMany(Audience::class, 'dossier_audience', 'id_dossier', 'id_audience');
    }

    public function historique()
    {
        return $this->hasMany(DossierHistorique::class, 'id_dossier', 'id_dossier')->latest();
    }

    public function decisions()
    {
        return $this->hasMany(Decision::class, 'id_dossier', 'id_dossier')->latest();
    }

    public function instructions()
    {
        return $this->hasMany(Instruction::class, 'id_dossier', 'id_dossier')->latest();
    }

    public function pjDocuments()
    {
        return $this->hasMany(PjDocument::class, 'id_dossier', 'id_dossier')->latest();
    }

    public function scopeRecherche($query, array $filtres)
    {
        if (!empty($filtres['q'])) {
            $q = $filtres['q'];
            $query->where(function ($sub) use ($q) {
                $sub->where('numero_rp', 'like', "%{$q}%")
                    ->orWhere('numero_registre', 'like', "%{$q}%")
                    ->orWhere('nature_infraction', 'like', "%{$q}%")
                    ->orWhere('parquet_competent', 'like', "%{$q}%")
                    ->orWhereHas('parties', function ($p) use ($q) {
                        $p->where('nom', 'like', "%{$q}%")
                          ->orWhere('prenom', 'like', "%{$q}%");
                    });
            });
        }

        if (!empty($filtres['registre'])) {
            $query->where('id_registre', $filtres['registre']);
        }

        if (!empty($filtres['statut'])) {
            $query->where('statut', $filtres['statut']);
        }

        if (!empty($filtres['date_du'])) {
            $query->whereDate('date_demande', '>=', $filtres['date_du']);
        }

        if (!empty($filtres['date_au'])) {
            $query->whereDate('date_demande', '<=', $filtres['date_au']);
        }

        return $query;
    }

    public static function statutsList(): array
    {
        return [
            'En cours', 'Orienté', 'En instruction',
            'Médiation', 'Classé', 'Jugé', 'Exécuté', 'Archivé',
        ];
    }
}
