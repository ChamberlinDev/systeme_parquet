<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>
<body style="font-family:Arial,sans-serif;background:#f4f4f4;margin:0;padding:0;">
<div style="max-width:600px;margin:30px auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1)">

    <div style="background:#1a1a2e;padding:24px 32px;text-align:center">
        <h1 style="color:#fff;margin:0;font-size:20px;letter-spacing:1px">CONVOCATION</h1>
        <p style="color:#aaa;margin:6px 0 0;font-size:13px">Système de gestion des dossiers du Parquet</p>
    </div>

    <div style="padding:32px">
        <p style="color:#333;font-size:15px">
            Madame, Monsieur <strong>{{ $partie->nom }} {{ $partie->prenom }}</strong>,
        </p>

        <p style="color:#555;line-height:1.6">
            Vous êtes convoqué(e) à comparaître devant le Parquet à l'audience dont les détails sont précisés ci-dessous.
            Votre présence est <strong>obligatoire</strong>.
        </p>

        <div style="background:#f8f9fa;border-left:4px solid #1a1a2e;padding:16px 20px;margin:24px 0;border-radius:0 6px 6px 0">
            <table style="width:100%;font-size:14px;color:#333">
                <tr>
                    <td style="padding:6px 0;font-weight:bold;width:40%">Date de l'audience</td>
                    <td style="padding:6px 0">{{ \Carbon\Carbon::parse($audience->date_audience)->translatedFormat('l d F Y') }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0;font-weight:bold">Salle</td>
                    <td style="padding:6px 0">{{ $audience->salle }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0;font-weight:bold">Type d'audience</td>
                    <td style="padding:6px 0">{{ ucfirst($audience->type_audience) }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0;font-weight:bold">Dossier</td>
                    <td style="padding:6px 0">{{ $dossier->numero_registre }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0;font-weight:bold">Votre rôle</td>
                    <td style="padding:6px 0">{{ $partie->role }}</td>
                </tr>
            </table>
        </div>

        <p style="color:#555;font-size:13px;line-height:1.6">
            En cas d'empêchement, veuillez contacter le greffe du parquet dans les meilleurs délais.
            Tout défaut de comparution sans motif légitime pourra entraîner les conséquences prévues par la loi.
        </p>

        <p style="color:#333;margin-top:24px">Fait par le Greffe du Parquet,<br>
        <strong>Système de Gestion des Dossiers</strong></p>
    </div>

    <div style="background:#f4f4f4;padding:16px 32px;text-align:center;font-size:11px;color:#999">
        Ce message est généré automatiquement par le Système de Gestion des Dossiers du Parquet.
        Merci de ne pas y répondre directement.
    </div>
</div>
</body>
</html>
