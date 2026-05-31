<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
@include('pdf._styles')
</head>
<body>

<div class="footer">
    Réquisitoire introductif — {{ $dossier->numero_rp }} — {{ now()->format('d/m/Y') }}
</div>

<div class="header">
    <div class="pays">République — Parquet Général</div>
    <div class="titre">Réquisitoire introductif</div>
    <div class="sous-titre">Saisine du Juge d'Instruction</div>
</div>

<div class="ref-bar">
    <div class="ref-left">
        <strong>N° RP :</strong> {{ $dossier->numero_rp }}<br>
        <strong>N° Registre :</strong> {{ $dossier->numero_registre }}
    </div>
    <div class="ref-right">
        Parquet : {{ $dossier->parquet_competent ?: '—' }}<br>
        Date de saisine : {{ \Carbon\Carbon::parse($dossier->date_orientation ?? now())->format('d/m/Y') }}
    </div>
</div>

<div class="box">
    <div class="box-title">RÉQUISITOIRE AUX FINS D'INFORMER</div>
    <p style="text-align:center;font-size:10px;margin-top:6px">
        Le Procureur de la République, vu les pièces ci-jointes,<br>
        <strong>requiert</strong> qu'il plaise au Juge d'Instruction d'ouvrir une information judiciaire<br>
        sur les faits exposés ci-après.
    </p>
</div>

@php $instruction = $dossier->instructions->first(); @endphp
@if($instruction && $instruction->juge)
<div class="section">
    <div class="section-title">Juge d'instruction désigné</div>
    <table class="info">
        <tr><td>Magistrat</td><td>{{ $instruction->juge->name }}</td></tr>
        <tr><td>Date de saisine</td><td>{{ \Carbon\Carbon::parse($instruction->date_saisine)->format('d/m/Y') }}</td></tr>
    </table>
</div>
@endif

<div class="section">
    <div class="section-title">Qualification des faits</div>
    <div class="motif">{{ $dossier->nature_infraction ?: 'Non précisée' }}</div>
</div>

<div class="section">
    <div class="section-title">Exposé des faits et motifs</div>
    <div class="motif">{{ $dossier->motif_orientation ?: 'Non renseigné' }}</div>
</div>

<div class="section">
    <div class="section-title">Personnes mises en cause</div>
    <table class="parties">
        <thead><tr><th>Nom</th><th>Prénom</th><th>Rôle</th><th>Contact</th></tr></thead>
        <tbody>
            @forelse($dossier->parties as $p)
            <tr>
                <td>{{ strtoupper($p->nom) }}</td>
                <td>{{ $p->prenom ?: '—' }}</td>
                <td>{{ $p->role }}</td>
                <td>{{ $p->contact ?: '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="4">Aucune partie enregistrée</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-title">Pièces jointes au dossier</div>
    <table class="info">
        <tr><td>Nombre de fichiers</td><td>{{ $dossier->files->count() }} pièce(s)</td></tr>
    </table>
</div>

<p style="font-size:10px;margin-top:16px">
    En conséquence, le Parquet requiert qu'il soit procédé à toutes investigations utiles,
    notamment auditions, expertises et commissions rogatoires, afin de faire la lumière sur
    les faits exposés et de déterminer les responsabilités.
</p>

<div class="signatures">
    <div class="sig-col">
        <div class="sig-title">Le Greffier du Parquet</div>
        <div class="sig-box">Signature &amp; cachet</div>
        <div class="sig-line">Nom : ................................</div>
    </div>
    <div class="sig-col"></div>
    <div class="sig-col">
        <div class="sig-title">Le Procureur de la République</div>
        <div class="sig-box">Signature</div>
        <div class="sig-line">{{ $procureur->name }}</div>
    </div>
</div>

</body>
</html>
