<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
@include('pdf._styles')
</head>
<body>

<div class="footer">
    Jugement — {{ $dossier->numero_rp ?? '—' }} — {{ now()->format('d/m/Y') }}
</div>

<div class="header">
    <div class="pays">République — Au nom du Peuple</div>
    <div class="titre">Jugement</div>
    <div class="sous-titre">
        Tribunal correctionnel — Audience du
        {{ $decision->audience ? \Carbon\Carbon::parse($decision->audience->date_audience)->format('d/m/Y') : '—' }}
    </div>
</div>

<div class="ref-bar">
    <div class="ref-left">
        <strong>N° RP :</strong> {{ $dossier->numero_rp ?? '—' }}<br>
        <strong>N° Registre :</strong> {{ $dossier->numero_registre ?? '—' }}
    </div>
    <div class="ref-right">
        Date du jugement : {{ \Carbon\Carbon::parse($decision->date_decision)->format('d/m/Y') }}<br>
        Juge signataire : {{ $decision->signatures }}
    </div>
</div>

<div class="section">
    <div class="section-title">Identification du dossier</div>
    <table class="info">
        <tr><td>Registre</td><td>{{ $dossier->registre->nom ?? '—' }}</td></tr>
        <tr><td>Nature de l'infraction</td><td>{{ $dossier->nature_infraction ?: '—' }}</td></tr>
        <tr><td>Parquet compétent</td><td>{{ $dossier->parquet_competent ?: '—' }}</td></tr>
    </table>
</div>

<div class="section">
    <div class="section-title">Parties en cause</div>
    <table class="parties">
        <thead><tr><th>Nom</th><th>Prénom</th><th>Rôle</th></tr></thead>
        <tbody>
            @forelse($dossier->parties as $p)
            <tr>
                <td>{{ strtoupper($p->nom) }}</td>
                <td>{{ $p->prenom ?: '—' }}</td>
                <td>{{ $p->role }}</td>
            </tr>
            @empty
            <tr><td colspan="3">Aucune partie enregistrée</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-title">Dispositif du jugement</div>
    <div class="motif" style="min-height:200px;white-space:pre-wrap;">{{ $decision->contenu }}</div>
</div>

<p style="font-size:10px;margin-top:10px">
    Le présent jugement a été rendu publiquement, en audience du
    {{ $decision->audience ? \Carbon\Carbon::parse($decision->audience->date_audience)->translatedFormat('l d F Y') : '—' }},
    par le Tribunal siégeant en formation {{ $decision->audience->type_audience ?? '' }}.
</p>

<div class="signatures">
    <div class="sig-col">
        <div class="sig-title">Le Greffier d'Audience</div>
        <div class="sig-box">Signature &amp; cachet</div>
        <div class="sig-line">Nom : ................................</div>
    </div>
    <div class="sig-col"></div>
    <div class="sig-col">
        <div class="sig-title">Le Juge</div>
        <div class="sig-box">Signature</div>
        <div class="sig-line">{{ $decision->signatures }}</div>
    </div>
</div>

</body>
</html>
