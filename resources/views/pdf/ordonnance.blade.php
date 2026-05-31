<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
@include('pdf._styles')
</head>
<body>

<div class="footer">
    Ordonnance — {{ $dossier->numero_rp ?? '—' }} — {{ now()->format('d/m/Y') }}
</div>

<div class="header">
    <div class="pays">République — Au nom du Peuple</div>
    <div class="titre">Ordonnance</div>
    <div class="sous-titre">
        Chambre {{ $decision->audience->type_audience ?? '' }}
        — {{ \Carbon\Carbon::parse($decision->date_decision)->format('d/m/Y') }}
    </div>
</div>

<div class="ref-bar">
    <div class="ref-left">
        <strong>N° RP :</strong> {{ $dossier->numero_rp ?? '—' }}<br>
        <strong>N° Registre :</strong> {{ $dossier->numero_registre ?? '—' }}
    </div>
    <div class="ref-right">
        Date : {{ \Carbon\Carbon::parse($decision->date_decision)->format('d/m/Y') }}<br>
        Signataire : {{ $decision->signatures }}
    </div>
</div>

<div class="section">
    <div class="section-title">Dossier concerné</div>
    <table class="info">
        <tr><td>Registre</td><td>{{ $dossier->registre->nom ?? '—' }}</td></tr>
        <tr><td>Nature des faits</td><td>{{ $dossier->nature_infraction ?: '—' }}</td></tr>
    </table>
</div>

<div class="section">
    <div class="section-title">Parties</div>
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
            <tr><td colspan="3">—</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-title">Dispositif de l'ordonnance</div>
    <div class="motif" style="min-height:200px;white-space:pre-wrap;">{{ $decision->contenu }}</div>
</div>

<p style="font-size:10px;margin-top:12px;font-style:italic">
    La présente ordonnance peut faire l'objet d'un recours dans les délais prévus
    par la loi auprès de la juridiction compétente.
</p>

<div class="signatures">
    <div class="sig-col">
        <div class="sig-title">Le Greffier</div>
        <div class="sig-box">Signature &amp; cachet</div>
        <div class="sig-line">Nom : ................................</div>
    </div>
    <div class="sig-col"></div>
    <div class="sig-col">
        <div class="sig-title">Le Juge d'Instruction</div>
        <div class="sig-box">Signature &amp; sceau</div>
        <div class="sig-line">{{ $decision->signatures }}</div>
    </div>
</div>

</body>
</html>
