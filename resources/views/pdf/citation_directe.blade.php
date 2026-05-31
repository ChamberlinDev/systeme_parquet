<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
@include('pdf._styles')
</head>
<body>

<div class="footer">
    Citation directe — {{ $dossier->numero_rp }} — Généré le {{ now()->format('d/m/Y à H:i') }}
</div>

<div class="header">
    <div class="pays">République — Parquet Général</div>
    <div class="titre">Citation directe</div>
    <div class="sous-titre">Article 394 et suivants du Code de Procédure Pénale</div>
</div>

<div class="ref-bar">
    <div class="ref-left">
        <strong>N° RP :</strong> {{ $dossier->numero_rp }}<br>
        <strong>N° Registre :</strong> {{ $dossier->numero_registre }}
    </div>
    <div class="ref-right">
        Parquet : {{ $dossier->parquet_competent ?: 'Non précisé' }}<br>
        Date : {{ \Carbon\Carbon::parse($dossier->date_orientation ?? $dossier->date_demande)->format('d/m/Y') }}
    </div>
</div>

<div class="box">
    <div class="box-title">CITATION À COMPARAÎTRE</div>
    <p style="text-align:center;font-size:11px;margin-top:6px">
        Le Procureur de la République près le Tribunal compétent<br>
        <strong>cite à comparaître</strong> devant le Tribunal Correctionnel les personnes désignées ci-après.
    </p>
</div>

<div class="section">
    <div class="section-title">Prévenu(s) cité(s)</div>
    @php $prevenus = $dossier->parties->where('role', 'Prévenu'); @endphp
    @if($prevenus->isNotEmpty())
    <table class="parties">
        <thead><tr><th>Nom</th><th>Prénom</th><th>Contact</th></tr></thead>
        <tbody>
            @foreach($prevenus as $p)
            <tr>
                <td>{{ strtoupper($p->nom) }}</td>
                <td>{{ $p->prenom ?: '—' }}</td>
                <td>{{ $p->contact ?: '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <table class="info"><tr><td colspan="2">Aucun prévenu désigné</td></tr></table>
    @endif
</div>

<div class="section">
    <div class="section-title">Faits reprochés</div>
    <div class="motif">{{ $dossier->nature_infraction ?: 'Nature de l\'infraction non précisée' }}</div>
</div>

<div class="section">
    <div class="section-title">Motif de la citation</div>
    <div class="motif">{{ $dossier->motif_orientation ?: 'Non renseigné' }}</div>
</div>

<div class="section">
    <div class="section-title">Registre et qualification</div>
    <table class="info">
        <tr><td>Registre</td><td>{{ $dossier->registre->nom ?? '—' }}</td></tr>
        <tr><td>Référence</td><td>{{ $dossier->numero_registre }}</td></tr>
        <tr><td>Date d'enregistrement</td><td>{{ \Carbon\Carbon::parse($dossier->date_demande)->format('d/m/Y') }}</td></tr>
    </table>
</div>

<div class="section">
    <div class="section-title">Parties civiles / Plaignants</div>
    @php $plaignants = $dossier->parties->whereNotIn('role', ['Prévenu']); @endphp
    @if($plaignants->isNotEmpty())
    <table class="parties">
        <thead><tr><th>Nom</th><th>Prénom</th><th>Rôle</th></tr></thead>
        <tbody>
            @foreach($plaignants as $p)
            <tr>
                <td>{{ strtoupper($p->nom) }}</td>
                <td>{{ $p->prenom ?: '—' }}</td>
                <td>{{ $p->role }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="font-size:10px;color:#666">Aucune partie civile enregistrée.</p>
    @endif
</div>

<div class="signatures">
    <div class="sig-col">
        <div class="sig-title">Le Greffier</div>
        <div class="sig-box">Signature &amp; cachet</div>
        <div class="sig-line">Nom : ................................</div>
    </div>
    <div class="sig-col">
        <div class="sig-title">Le Procureur de la République</div>
        <div class="sig-box">Signature</div>
        <div class="sig-line">{{ $procureur->name }}</div>
    </div>
    <div class="sig-col">
        <div class="sig-title">Date &amp; cachet du Parquet</div>
        <div class="sig-box">{{ now()->format('d/m/Y') }}</div>
        <div class="sig-line">Cachet officiel</div>
    </div>
</div>

</body>
</html>
