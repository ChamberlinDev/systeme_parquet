<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; color: #1a1a1a; }

    .header { text-align: center; border-bottom: 2px solid #1a1a1a; padding-bottom: 12px; margin-bottom: 20px; }
    .header .republique { font-size: 10px; letter-spacing: 2px; text-transform: uppercase; }
    .header .titre-doc { font-size: 18px; font-weight: bold; margin: 8px 0 4px; text-transform: uppercase; letter-spacing: 1px; }
    .header .sous-titre { font-size: 11px; color: #444; }

    .references { display: table; width: 100%; margin-bottom: 20px; }
    .ref-left { display: table-cell; width: 50%; }
    .ref-right { display: table-cell; width: 50%; text-align: right; font-size: 11px; }

    .section { margin-bottom: 16px; }
    .section-title { font-size: 11px; font-weight: bold; text-transform: uppercase;
        letter-spacing: 1px; border-bottom: 1px solid #ccc; padding-bottom: 4px; margin-bottom: 8px; color: #333; }

    table.info { width: 100%; border-collapse: collapse; font-size: 11px; }
    table.info td { padding: 5px 8px; }
    table.info td:first-child { font-weight: bold; width: 38%; color: #444; }
    table.info tr:nth-child(even) { background: #f7f7f7; }

    .decision-box { border: 2px solid #1a1a1a; padding: 14px 18px; margin: 20px 0; text-align: center; }
    .decision-box .type { font-size: 15px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
    .decision-box .date { font-size: 11px; margin-top: 4px; color: #444; }

    .motif-box { background: #f5f5f5; border-left: 4px solid #1a1a1a; padding: 12px 16px;
        margin-bottom: 20px; font-size: 11px; line-height: 1.6; }

    .signatures { display: table; width: 100%; margin-top: 40px; }
    .sig-left { display: table-cell; width: 50%; text-align: center; }
    .sig-right { display: table-cell; width: 50%; text-align: center; }
    .sig-title { font-size: 11px; font-weight: bold; text-transform: uppercase; margin-bottom: 60px; }
    .sig-line { border-top: 1px solid #1a1a1a; padding-top: 6px; font-size: 10px; margin: 0 20px; }

    .footer { position: fixed; bottom: 20px; left: 0; right: 0; text-align: center;
        font-size: 9px; color: #999; border-top: 1px solid #eee; padding-top: 6px; }

    .parties-table { width: 100%; border-collapse: collapse; font-size: 11px; }
    .parties-table th { background: #1a1a1a; color: white; padding: 6px 8px; text-align: left; font-size: 10px; }
    .parties-table td { padding: 5px 8px; border-bottom: 1px solid #eee; }
</style>
</head>
<body>

<div class="footer">
    Système de Gestion des Dossiers du Parquet &mdash;
    Document généré le {{ now()->format('d/m/Y à H:i') }} &mdash;
    {{ $dossier->numero_rp }}
</div>

{{-- En-tête --}}
<div class="header">
    <div class="republique">République — Parquet Général</div>
    <div class="titre-doc">Acte d'orientation</div>
    <div class="sous-titre">Décision du Ministère Public</div>
</div>

{{-- Références --}}
<div class="references">
    <div class="ref-left">
        <strong>N° RP :</strong> {{ $dossier->numero_rp }}<br>
        <strong>N° Registre :</strong> {{ $dossier->numero_registre }}
    </div>
    <div class="ref-right">
        Parquet : {{ $dossier->parquet_competent ?: 'Non précisé' }}<br>
        Date enregistrement : {{ \Carbon\Carbon::parse($dossier->date_demande)->format('d/m/Y') }}
    </div>
</div>

{{-- Décision d'orientation --}}
<div class="decision-box">
    <div class="type">{{ $labels[$dossier->decision_orientation] ?? $dossier->decision_orientation }}</div>
    <div class="date">
        Décision prise le {{ \Carbon\Carbon::parse($dossier->date_orientation)->format('d/m/Y') }}
    </div>
</div>

{{-- Informations du dossier --}}
<div class="section">
    <div class="section-title">Informations du dossier</div>
    <table class="info">
        <tr><td>Registre</td><td>{{ $dossier->registre->nom ?? '-' }}</td></tr>
        <tr><td>Nature de l'infraction</td><td>{{ $dossier->nature_infraction ?: 'Non précisée' }}</td></tr>
        <tr><td>Statut après décision</td><td>{{ $dossier->statut }}</td></tr>
    </table>
</div>

{{-- Parties --}}
@if($dossier->parties->isNotEmpty())
<div class="section">
    <div class="section-title">Parties concernées</div>
    <table class="parties-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Rôle</th>
                <th>Contact</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dossier->parties as $p)
            <tr>
                <td>{{ $p->nom }}</td>
                <td>{{ $p->prenom ?: '—' }}</td>
                <td>{{ $p->role }}</td>
                <td>{{ $p->contact ?: '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- Motif --}}
<div class="section">
    <div class="section-title">Motif de la décision</div>
    <div class="motif-box">{{ $dossier->motif_orientation }}</div>
</div>

{{-- Signatures --}}
<div class="signatures">
    <div class="sig-left">
        <div class="sig-title">Le Greffier du Parquet</div>
        <div class="sig-line">Signature &amp; cachet</div>
    </div>
    <div class="sig-right">
        <div class="sig-title">Le Procureur de la République</div>
        <div class="sig-line">{{ $procureur->name }}</div>
    </div>
</div>

</body>
</html>
