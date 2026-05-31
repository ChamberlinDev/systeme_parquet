<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
@include('pdf._styles')
<style>
.mandat-cadre { border: 3px double #1a1a1a; padding: 16px; text-align: center; margin: 16px 0; }
.mandat-cadre .type { font-size: 16px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; }
</style>
</head>
<body>

<div class="footer">
    Mandat de dépôt — {{ $dossier->numero_rp ?? '—' }} — {{ now()->format('d/m/Y') }}
</div>

<div class="header">
    <div class="pays">République — Au nom du Peuple</div>
    <div class="titre">Mandat de dépôt</div>
    <div class="sous-titre">Titre exécutoire — Peine privative de liberté</div>
</div>

<div class="ref-bar">
    <div class="ref-left">
        <strong>N° RP :</strong> {{ $dossier->numero_rp ?? '—' }}<br>
        <strong>Exécution n° :</strong> {{ $execution->id_execution }}
    </div>
    <div class="ref-right">
        Institution : {{ $execution->institution->nom ?? '—' }}<br>
        Date d'émission : {{ now()->format('d/m/Y') }}
    </div>
</div>

<div class="mandat-cadre">
    <div class="type">MANDAT DE DÉPÔT</div>
    <p style="font-size:10px;margin-top:8px">
        Délivré en vertu de la décision judiciaire ci-après référencée,<br>
        le Parquet ordonne la mise à exécution de la peine privative de liberté.
    </p>
</div>

<div class="section">
    <div class="section-title">Décision judiciaire</div>
    <table class="info">
        <tr><td>Type</td><td>{{ ucfirst($execution->decision->type_decision ?? '—') }}</td></tr>
        <tr><td>Date</td><td>{{ \Carbon\Carbon::parse($execution->decision->date_decision ?? now())->format('d/m/Y') }}</td></tr>
        <tr><td>Signataire</td><td>{{ $execution->decision->signatures ?? '—' }}</td></tr>
    </table>
</div>

<div class="section">
    <div class="section-title">Dossier</div>
    <table class="info">
        <tr><td>Numéro RP</td><td>{{ $dossier->numero_rp ?? '—' }}</td></tr>
        <tr><td>Registre</td><td>{{ $dossier->registre->nom ?? '—' }}</td></tr>
        <tr><td>Nature de l'infraction</td><td>{{ $dossier->nature_infraction ?: '—' }}</td></tr>
    </table>
</div>

<div class="section">
    <div class="section-title">Personne(s) concernée(s)</div>
    @php $prevenus = $dossier->parties->where('role', 'Prévenu'); @endphp
    <table class="parties">
        <thead><tr><th>Nom</th><th>Prénom</th><th>Contact</th></tr></thead>
        <tbody>
            @forelse($prevenus as $p)
            <tr>
                <td>{{ strtoupper($p->nom) }}</td>
                <td>{{ $p->prenom ?: '—' }}</td>
                <td>{{ $p->contact ?: '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="3">Non précisé</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-title">Établissement destinataire</div>
    <table class="info">
        <tr><td>Institution</td><td>{{ $execution->institution->nom ?? '—' }}</td></tr>
        <tr><td>Type</td><td>{{ $execution->institution->type_institution ?? '—' }}</td></tr>
        @if($execution->date_execution)
        <tr><td>Date prévue</td><td>{{ \Carbon\Carbon::parse($execution->date_execution)->format('d/m/Y') }}</td></tr>
        @endif
    </table>
</div>

<p style="font-size:10px;margin-top:16px;font-style:italic">
    Le présent mandat a force exécutoire dès sa remise. Tout obstacle à son exécution
    doit être signalé immédiatement au Parquet.
</p>

<div class="signatures">
    <div class="sig-col">
        <div class="sig-title">Le Greffier</div>
        <div class="sig-box">Signature &amp; cachet</div>
        <div class="sig-line">Nom : ................................</div>
    </div>
    <div class="sig-col"></div>
    <div class="sig-col">
        <div class="sig-title">Le Procureur de la République</div>
        <div class="sig-box">Signature &amp; sceau</div>
        <div class="sig-line">Cachet officiel du Parquet</div>
    </div>
</div>

</body>
</html>
