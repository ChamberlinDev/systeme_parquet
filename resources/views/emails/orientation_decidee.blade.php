<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;background:#f4f4f4;margin:0;padding:0;">
<div style="max-width:600px;margin:30px auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1)">

    <div style="background:#212529;padding:24px 32px;text-align:center">
        <h1 style="color:#fff;margin:0;font-size:20px">DÉCISION D'ORIENTATION</h1>
        <p style="color:#aaa;margin:6px 0 0;font-size:13px">Système de gestion des dossiers du Parquet</p>
    </div>

    <div style="padding:32px">
        <p style="color:#333;font-size:15px">Monsieur/Madame le Greffier,</p>

        <p style="color:#555;line-height:1.6">
            Le procureur a rendu une décision d'orientation sur le dossier suivant.
            Veuillez prendre les suites nécessaires.
        </p>

        <div style="background:#f8f9fa;border-left:4px solid #212529;padding:16px 20px;margin:24px 0;border-radius:0 6px 6px 0">
            <table style="width:100%;font-size:14px;color:#333">
                <tr>
                    <td style="padding:6px 0;font-weight:bold;width:40%">Dossier</td>
                    <td style="padding:6px 0">{{ $dossier->numero_rp }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0;font-weight:bold">Décision</td>
                    <td style="padding:6px 0">
                        <strong style="color:#dc3545">{{ $labelOrientation }}</strong>
                    </td>
                </tr>
                <tr>
                    <td style="padding:6px 0;font-weight:bold">Date</td>
                    <td style="padding:6px 0">{{ \Carbon\Carbon::parse($dossier->date_orientation)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0;font-weight:bold">Nouveau statut</td>
                    <td style="padding:6px 0">{{ $dossier->statut }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0;font-weight:bold;vertical-align:top">Motif</td>
                    <td style="padding:6px 0">{{ $dossier->motif_orientation }}</td>
                </tr>
            </table>
        </div>

        <p style="color:#555;font-size:13px;line-height:1.6">
            Connectez-vous au système pour consulter le dossier et effectuer les actes de greffe requis.
        </p>

        <p style="color:#333;margin-top:24px">Le Parquet</p>
    </div>

    <div style="background:#f4f4f4;padding:16px 32px;text-align:center;font-size:11px;color:#999">
        Notification automatique — Système de Gestion des Dossiers du Parquet
    </div>
</div>
</body>
</html>
