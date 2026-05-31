<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
@include('pdf._styles')
</head>
<body>

<div class="footer">
    Notification de décision — {{ $dossier->numero_rp ?? '—' }} — {{ now()->format('d/m/Y') }}
</div>

<div class="header">
    <div class="pays">République — Greffe du Parquet</div>
    <div class="titre">Notification de décision judiciaire</div>
    <div class="sous-titre">Document officiel — À conserver</div>
</div>

<div class="ref-bar">
    <div class="ref-left">
        <strong>N° RP :</strong> {{ $dossier->numero_rp ?? '—' }}<br>
        <strong>N° Registre :</strong> {{ $dossier->numero_registre ?? '—' }}
    </div>
    <div class="ref-right">
        Date de notification : {{ now()->format('d/m/Y') }}<br>
        Décision du : {{ \Carbon\Carbon::parse($decision->date_decision)->format('d/m/Y') }}
    </div>
</div>

@foreach($dossier->parties as $partie)
<div class="box" style="margin-bottom:10px">
    <p style="font-size:11px">
        <strong>À l'attention de :</strong>
        {{ strtoupper($partie->nom) }} {{ $partie->prenom }}
        <span style="color:#666">({{ $partie->role }})</span>
    </p>
</div>

<p style="font-size:11px;margin-bottom:14px">
    Madame, Monsieur <strong>{{ $partie->nom }} {{ $partie->prenom }}</strong>,
</p>

<p style="font-size:10px;line-height:1.7;margin-bottom:14px">
    Nous avons l'honneur de vous notifier la décision judiciaire rendue par la juridiction compétente
    dans l'affaire vous concernant, référencée sous le numéro <strong>{{ $dossier->numero_rp }}</strong>.
</p>

<div class="section">
    <div class="section-title">Nature de la décision</div>
    <table class="info">
        <tr><td>Type</td><td><strong>{{ ucfirst($decision->type_decision) }}</strong></td></tr>
        <tr><td>Date</td><td>{{ \Carbon\Carbon::parse($decision->date_decision)->format('d/m/Y') }}</td></tr>
        <tr><td>Juridiction</td><td>{{ $dossier->parquet_competent ?: '—' }}</td></tr>
    </table>
</div>

<div class="section">
    <div class="section-title">Dispositif</div>
    <div class="motif" style="white-space:pre-wrap;">{{ $decision->contenu }}</div>
</div>

<p style="font-size:10px;line-height:1.7;margin-top:14px">
    La présente notification vous est adressée conformément aux dispositions du Code de Procédure Pénale.
    Vous disposez d'un délai légal pour exercer les voies de recours prévues par la loi.
    Pour toute information complémentaire, veuillez contacter le Greffe du Parquet.
</p>

<div class="signatures" style="margin-top:24px">
    <div class="sig-col">
        <div class="sig-title">Le Greffier du Parquet</div>
        <div class="sig-box">Signature &amp; cachet</div>
        <div class="sig-line">Nom : ................................</div>
    </div>
    <div class="sig-col">
        <div style="margin-top:10px;text-align:center;font-size:10px;color:#666">
            <strong>Accusé de réception</strong><br>
            <br>
            Lu et reçu le : ................<br>
            <br>
            Signature du destinataire :<br>
            <div style="border-top:1px solid #1a1a1a;margin-top:40px;font-size:9px">
                {{ $partie->nom }} {{ $partie->prenom }}
            </div>
        </div>
    </div>
    <div class="sig-col">
        <div class="sig-title">Le Procureur de la République</div>
        <div class="sig-box">Signature</div>
        <div class="sig-line">Cachet officiel</div>
    </div>
</div>

@if(!$loop->last)
<div class="page-break"></div>
@endif
@endforeach

</body>
</html>
