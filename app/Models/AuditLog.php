<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_log';

    // Journal immuable — pas de updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'action',
        'objet_type',
        'objet_id',
        'detail',
        'ip_address',
        'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Codes couleur par catégorie d'action
    public static array $couleurs = [
        'CONNEXION'            => 'success',
        'DECONNEXION'          => 'secondary',
        'MOT_DE_PASSE'         => 'warning',
        'CREATION_DOSSIER'     => 'primary',
        'CONSULTATION_DOSSIER' => 'info',
        'ORIENTATION'          => 'dark',
        'CREATION_AUDIENCE'    => 'primary',
        'DECISION'             => 'success',
        'CREATION_EXECUTION'   => 'warning',
        'MAJ_EXECUTION'        => 'info',
        'ARCHIVAGE'            => 'secondary',
        'INSTRUCTION'          => 'dark',
        'CREATION_UTILISATEUR' => 'primary',
        'MAJ_UTILISATEUR'      => 'warning',
        'ACTE_INSTRUCTION'     => 'info',
        'DETENTION'            => 'danger',
        'MESSAGE'              => 'light',
    ];

    public static array $labels = [
        'CONNEXION'            => 'Connexion',
        'DECONNEXION'          => 'Déconnexion',
        'MOT_DE_PASSE'         => 'Changement mdp',
        'CREATION_DOSSIER'     => 'Création dossier',
        'CONSULTATION_DOSSIER' => 'Consultation',
        'ORIENTATION'          => 'Orientation',
        'CREATION_AUDIENCE'    => 'Audience créée',
        'DECISION'             => 'Décision rendue',
        'CREATION_EXECUTION'   => 'Exécution créée',
        'MAJ_EXECUTION'        => 'Exécution mise à jour',
        'ARCHIVAGE'            => 'Archivage',
        'INSTRUCTION'          => 'Instruction',
        'CREATION_UTILISATEUR' => 'Utilisateur créé',
        'MAJ_UTILISATEUR'      => 'Utilisateur modifié',
        'ACTE_INSTRUCTION'     => 'Acte instruction',
        'DETENTION'            => 'Détention',
        'MESSAGE'              => 'Message',
    ];
}
