<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    /**
     * Enregistre une entrée dans le journal d'audit.
     *
     * @param string      $action     Code de l'action (CONNEXION, CREATION_DOSSIER…)
     * @param string|null $objetType  Nom du modèle concerné (Dossier, User…)
     * @param int|null    $objetId    Identifiant de l'objet
     * @param string|null $detail     Description libre
     */
    public static function log(
        string  $action,
        ?string $objetType = null,
        ?int    $objetId   = null,
        ?string $detail    = null,
    ): void {
        try {
            AuditLog::create([
                'user_id'    => Auth::id(),
                'action'     => $action,
                'objet_type' => $objetType,
                'objet_id'   => $objetId,
                'detail'     => $detail,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
