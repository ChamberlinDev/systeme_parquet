<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;background:#f4f4f4;margin:0;padding:0;">
<div style="max-width:600px;margin:30px auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1)">

    <div style="background:#198754;padding:24px 32px;text-align:center">
        <h1 style="color:#fff;margin:0;font-size:20px">ORDRE D'EXÉCUTION</h1>
        <p style="color:#d1e7dd;margin:6px 0 0;font-size:13px">Système de gestion des dossiers du Parquet</p>
    </div>

    <div style="padding:32px">
        <p style="color:#333;font-size:15px">Madame, Monsieur,</p>

        <p style="color:#555;line-height:1.6">
            Le Parquet vous adresse un ordre d'exécution de peine dans le cadre du dossier référencé ci-dessous.
            Veuillez prendre les dispositions nécessaires à sa mise en œuvre dans les meilleurs délais.
        </p>

        @php
        $peineLabels = [
            'privative_liberte' => 'Peine privative de liberté',
            'pecuniaire'        => 'Peine pécuniaire (amende)',
            'complementaire'    => 'Peine complémentaire',
        ];
        $dossier = $execution->decision->dossier ?? null;
        @endphp

        <div style="background:#f8f9fa;border-left:4px solid #198754;padding:16px 20px;margin:24px 0;border-radius:0 6px 6px 0">
            <table style="width:100%;font-size:14px;color:#333">
                @if($dossier)
                <tr>
                    <td style="padding:6px 0;font-weight:bold;width:40%">Dossier</td>
                    <td style="padding:6px 0">{{ $dossier->numero_rp }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding:6px 0;font-weight:bold">Type de peine</td>
                    <td style="padding:6px 0"><strong>{{ $peineLabels[$execution->type_peine] ?? $execution->type_peine }}</strong></td>
                </tr>
                <tr>
                    <td style="padding:6px 0;font-weight:bold">Institution</td>
                    <td style="padding:6px 0">{{ $execution->institution->nom ?? '—' }}</td>
                </tr>
                @if($execution->date_execution)
                <tr>
                    <td style="padding:6px 0;font-weight:bold">Date prévue</td>
                    <td style="padding:6px 0">{{ \Carbon\Carbon::parse($execution->date_execution)->format('d/m/Y') }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding:6px 0;font-weight:bold">Référence exécution</td>
                    <td style="padding:6px 0">#{{ $execution->id_execution }}</td>
                </tr>
            </table>
        </div>

        <p style="color:#555;font-size:13px;line-height:1.6">
            Tout retard dans l'exécution devra être signalé au greffe du parquet accompagné des justifications nécessaires.
        </p>

        <p style="color:#333;margin-top:24px">Le Greffier du Parquet</p>
    </div>

    <div style="background:#f4f4f4;padding:16px 32px;text-align:center;font-size:11px;color:#999">
        Notification automatique — Système de Gestion des Dossiers du Parquet
    </div>
</div>
</body>
</html>
