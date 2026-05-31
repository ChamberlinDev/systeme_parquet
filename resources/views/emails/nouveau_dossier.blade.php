<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;background:#f4f4f4;margin:0;padding:0;">
<div style="max-width:600px;margin:30px auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1)">

    <div style="background:#0d6efd;padding:24px 32px;text-align:center">
        <h1 style="color:#fff;margin:0;font-size:20px">NOUVEAU DOSSIER ENREGISTRÉ</h1>
        <p style="color:#cce5ff;margin:6px 0 0;font-size:13px">Système de gestion des dossiers du Parquet</p>
    </div>

    <div style="padding:32px">
        <p style="color:#333;font-size:15px">Monsieur/Madame le Procureur,</p>

        <p style="color:#555;line-height:1.6">
            Un nouveau dossier a été enregistré dans le système et est en attente de votre décision d'orientation.
        </p>

        <div style="background:#f8f9fa;border-left:4px solid #0d6efd;padding:16px 20px;margin:24px 0;border-radius:0 6px 6px 0">
            <table style="width:100%;font-size:14px;color:#333">
                <tr>
                    <td style="padding:6px 0;font-weight:bold;width:40%">Numéro RP</td>
                    <td style="padding:6px 0">{{ $dossier->numero_rp }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0;font-weight:bold">Numéro registre</td>
                    <td style="padding:6px 0">{{ $dossier->numero_registre }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0;font-weight:bold">Nature de l'infraction</td>
                    <td style="padding:6px 0">{{ $dossier->nature_infraction ?: 'Non précisée' }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0;font-weight:bold">Date d'enregistrement</td>
                    <td style="padding:6px 0">{{ \Carbon\Carbon::parse($dossier->date_demande)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0;font-weight:bold">Parquet compétent</td>
                    <td style="padding:6px 0">{{ $dossier->parquet_competent ?: 'Non précisé' }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0;font-weight:bold">Nombre de parties</td>
                    <td style="padding:6px 0">{{ $dossier->parties->count() }}</td>
                </tr>
            </table>
        </div>

        <p style="color:#555;font-size:13px;line-height:1.6">
            Veuillez vous connecter au système pour consulter le dossier et prendre votre décision d'orientation.
        </p>

        <p style="color:#333;margin-top:24px">Le Greffe du Parquet</p>
    </div>

    <div style="background:#f4f4f4;padding:16px 32px;text-align:center;font-size:11px;color:#999">
        Notification automatique — Système de Gestion des Dossiers du Parquet
    </div>
</div>
</body>
</html>
