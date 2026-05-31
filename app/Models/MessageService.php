<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageService extends Model
{
    protected $table = 'messages_service';

    protected $fillable = [
        'expediteur_id',
        'service_destinataire',
        'objet',
        'contenu',
        'id_dossier',
        'lu',
    ];

    protected $casts = [
        'lu' => 'boolean',
    ];

    public function expediteur()
    {
        return $this->belongsTo(User::class, 'expediteur_id');
    }

    public function dossier()
    {
        return $this->belongsTo(Dossier::class, 'id_dossier', 'id_dossier');
    }

    public static array $services = [
        'parquet'       => 'Parquet',
        'greffe'        => 'Greffe',
        'pj'            => 'Police Judiciaire',
        'penitentiaire' => 'Administration Pénitentiaire',
        'tresor'        => 'Trésor Public',
        'juridiction'   => 'Juridiction',
    ];

    /**
     * Mappe un utilisateur (par ses rôles) vers son service.
     */
    public static function serviceForUser(User $user): ?string
    {
        return match (true) {
            $user->hasRole('procureur'), $user->hasRole('substitut') => 'parquet',
            $user->hasRole('greffier')          => 'greffe',
            $user->hasRole('police_judiciaire') => 'pj',
            $user->hasRole('penitentiaire')     => 'penitentiaire',
            $user->hasRole('tresor')            => 'tresor',
            $user->hasRole('juge')              => 'juridiction',
            default                             => null,
        };
    }
}
