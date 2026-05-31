# Taff - Systeme Parquet

---

## ETAT D'AVANCEMENT — mis a jour le 2026-05-31

Ce fichier retrace tout ce qui a ete implemente et tout ce qui reste a faire
pour couvrir l'integralite du cahier des charges `gestion_dossiers_parquet.md`.

**Les 7 etapes du workflow + les 8 priorites complementaires sont implementees.**

---

## CE QUI EST IMPLEMENTE

### Infrastructure & securite de base

- Authentification (login / logout)
- Changement de mot de passe force au premier login (middleware `force.password.change`)
- RBAC complet avec Spatie `laravel-permission` : 9 roles
  (admin, greffier, procureur, substitut, juge, police_judiciaire, huissier, penitentiaire, tresor)
- Vues et layouts separes par role (admin, greffier, procureur, juge + layout externe partage)
- Stockage fichiers PDF dans MinIO (S3-compatible) via Docker
- Compatibilite de lecture avec les anciens fichiers stockes en `public`
- Configuration Docker Compose avec MinIO
- Journal d'audit complet (connexions, actions sensibles, IP, user-agent)

---

### Etape 1 — Enregistrement du dossier (COMPLET)

- Numerotation automatique double :
  - Numero RP : `RP/annee/sequence`
  - Numero registre : `CODE/annee/sequence` (CORR, CRIM, CIV, etc.)
- Gestion des registres (CRUD complet par le greffier)
- Enregistrement des parties (nom, prenom, contact, role)
- Upload de pieces jointes PDF dans MinIO
- Page detail dossier : informations, parties, pieces jointes, apercu PDF inline
- Affectation automatique du dossier au greffier connecte
- Traçabilite initiale via `dossier_historique`
- Vues d'ajout separees : greffier, procureur, juge
- Notification email aux procureurs a la creation d'un nouveau dossier

---

### Etape 2 — Analyse et decision d'orientation (COMPLET)

- Formulaire d'orientation du procureur/substitut sur chaque dossier
- 6 types d'orientation implementes :
  - Classement sans suite → statut `Classe`
  - Citation directe → statut `Oriente`
  - Comparution immediate → statut `Oriente`
  - Requisitoire introductif → statut `En instruction`
  - Mediation penale → statut `Mediation`
  - Renvoi administratif → statut `Classe`
- Motif obligatoire + date de decision
- Statut du dossier mis a jour automatiquement
- Bouton "Decider orientation" / "Modifier l'orientation" sur la page detail
- Generation d'un acte d'orientation PDF (modele pre-rempli + zone de signature)
- Upload possible d'un acte signe par le procureur (stocke dans MinIO)
- Notification email au greffier apres orientation
- Historique horodate + journal d'audit

---

### Etape 3 — Instruction judiciaire (COMPLET)

- Ouverture d'instruction depuis un dossier oriente "requisitoire introductif"
- Saisine du juge d'instruction (selection du juge + date)
- Suivi des actes d'instruction : commission rogatoire, expertise, audition,
  confrontation, perquisition, autre — avec statut et resultat
- Calendrier des mesures de detention provisoire :
  - Alertes automatiques (< 7 jours = orange, depasse = rouge)
  - Prolongation motivee et levee de detention
- Remontees de la police judiciaire (mise a jour du statut des actes)
- Messagerie interne securisee juge ↔ procureur (marquage lu automatique)
- Cloture de l'instruction : renvoi (→ Oriente) ou non-lieu (→ Classe)
- 4 tables : instructions, actes_instruction, mesures_detention, messages_instruction

---

### Traçabilite transversale (COMPLET)

- Table `dossier_historique` : chaque action enregistree avec user, date, action, detail
- Affichage de l'historique sur la page detail du dossier
- Toutes les etapes tracees : orientation, audience, PV, decision, execution,
  archivage, instruction, depot PJ

---

### Etape 4 — Preparation de l'audience (COMPLET)

- Creation d'une audience : date, salle, type (correctionnelle, criminelle, sociale, refere)
- Selection multi-dossiers pour le role de l'audience
- Lien many-to-many Dossier — Audience via table pivot `dossier_audience`
- Liste des audiences par role : greffier, juge, procureur
- Convocations email automatiques aux parties (+ stub SMS)
- Historique + journal d'audit lors de la planification
- Statut dossier mis a jour vers `Oriente` si encore `En cours`

---

### Etape 5 — Audience et decision judiciaire (COMPLET)

- Saisie du PV d'audience par le greffier (modifiable)
- Formulaire de decision judiciaire par le juge, par dossier inscrit :
  - Types : jugement, ordonnance, arret
  - Contenu du dispositif, date, signature (juge connecte)
- Decision modifiable (updateOrCreate)
- Statut dossier → `Juge` automatiquement
- Generation PDF du jugement et de l'ordonnance (dispositif formate + signatures)
- Notification de decision PDF (une page par partie + accuse de reception)
- Historique + journal d'audit

---

### Etape 6 — Execution des decisions (COMPLET)

- Creation d'une execution par decision rendue :
  - Type de peine : privative de liberte, pecuniaire, complementaire
  - Institution executante : Administration penitentiaire, Tresor public, Huissiers
- Seeder `InstitutionSeeder` : 3 institutions par defaut (avec email)
- Mise a jour du statut d'execution : en_cours, executee, non_executee
- Date d'execution effective enregistree
- Cloture automatique du dossier (statut `Execute`) quand toutes les peines sont executees
- Generation PDF du mandat de depot/arret (peine privative)
- Notification email a l'institution executante
- Tableau de bord des executions avec 3 compteurs
- Historique + journal d'audit a chaque changement

---

### Etape 7 — Archivage et suivi statistique (COMPLET)

- Archivage controle des dossiers (statuts eligibles : Execute, Classe, Juge)
- Motif d'archivage obligatoire + date
- Action irreversible : statut → `Archive`
- Liste des archives consultable avec recherche par mots-cles
- Tableau de bord statistique (admin) :
  - 8 KPI cards : total, en cours, juge, execute, classe, archive, audiences, decisions
  - Graphe bar : enregistrements sur les 6 derniers mois (Chart.js)
  - Graphe doughnut : repartition par statut
  - Tableau : dossiers par registre avec barre de progression
  - Progress bars : repartition des types d'orientation
  - Graphe doughnut : taux d'execution
  - Tableau des 10 derniers dossiers enregistres
- Dashboards accueil avec vraies donnees (admin, procureur, juge, greffier, + 4 roles externes)

---

## PRIORITES COMPLEMENTAIRES — TOUTES IMPLEMENTEES

### Priorite 1 — Etape 3 : Instruction judiciaire (FAIT)

Ref. cahier : "Etape 3 : Instruction judiciaire et suivi" (pp. 333–348)

- [x] Module de saisine du juge d'instruction (transmission du requisitoire introductif)
- [x] Suivi des actes d'instruction : commissions rogatoires, expertises, auditions
- [x] Calendrier des mesures avec alertes automatiques sur les delais de detention provisoire
- [x] Gestion des demandes de mise en liberte et prolongations motivees
- [x] Remontees de la police judiciaire (execution des actes, rapports)
- [x] Messagerie interne securisee entre parquet, juge et police judiciaire

---

### Priorite 2 — Completion de l'etape 2 (orientation) (FAIT)

Ref. cahier : "Etape 2 : Analyse et decision d'orientation" (pp. 316–329)

- [x] Ajouter **mediation penale** comme type d'orientation (p. 321)
- [x] Ajouter **renvoi administratif** comme type d'orientation (p. 321)
- [x] Generation d'un acte PDF signe electroniquement (modele pre-rempli) (p. 322)
- [x] Upload possible d'un acte signe par le procureur

---

### Priorite 3 — Recherche et filtrage (FAIT)

Ref. cahier : "possibilite de recherche par mots-cles" (p. 263)

- [x] Moteur de recherche dans la liste des dossiers (numero, partie, infraction, date, statut)
- [x] Filtres par registre, statut, periode sur toutes les listes
- [x] Recherche dans les archives par mots-cles

---

### Priorite 4 — Notifications electroniques (FAIT)

Ref. cahier : "convocations envoyees par notification electronique (email, SMS)" (pp. 250, 271)

- [x] Envoi d'email de convocation aux parties lors de la planification d'une audience
- [x] Notification email au procureur lors de l'enregistrement d'un nouveau dossier
- [x] Notification email au greffier lors d'une decision d'orientation
- [x] Notification aux institutions lors de la creation d'une execution
- [x] Portail de notification electronique (stub SMS pret pour integration operateur)

---

### Priorite 5 — RBAC complet (FAIT)

Ref. cahier : "profils Parquet, Greffe, PJ, Huissier, Penitentiaire, Tresor, Juridictions, Administration" (p. 424)

- [x] Role **Substitut** : acces aux dossiers du procureur, saisie d'orientation
- [x] Role **Police judiciaire** : depot de PV et pieces, mise a jour etat enquete
- [x] Role **Huissier** : acces restreint aux convocations, retour de statut de signification
- [x] Role **Administration penitentiaire** : reception des mandats, retour d'etat de detention
- [x] Role **Tresor public** : reception des amendes, confirmation de paiement
- [x] Cloisonnement par parquet (un utilisateur ne voit que les dossiers de son parquet)

---

### Priorite 6 — Journal d'audit (FAIT)

Ref. cahier : "Journalisation complete : horodatage, adresse IP, action, objet, version de document" (p. 425)

- [x] Table `audit_log` : connexions avec IP, user-agent, date
- [x] Enregistrement de chaque action sensible (creation, modification, consultation)
- [x] Interface de consultation du journal d'audit (admin uniquement)
- [x] Export du journal en CSV (UTF-8 BOM compatible Excel)

---

### Priorite 7 — Generation d'actes PDF (FAIT)

Ref. cahier : "Template d'actes : citations, requisitoires, mandats, jugements, ordonnances, notifications" (p. 443)

- [x] Modele PDF de **citation directe** (convocation du prevenu)
- [x] Modele PDF de **requisitoire introductif**
- [x] Modele PDF de **mandat de depot ou d'arret**
- [x] Modele PDF de **jugement** (dispositif formate)
- [x] Modele PDF d'**ordonnance**
- [x] Modele PDF de **notification de decision** aux parties (1 page/partie + accuse)
- [x] Zone de signature sur les actes (greffier, magistrat, cachet officiel)

---

### Priorite 8 — Portails externes (FAIT)

Ref. cahier : "Integrations recommandees" (pp. 432–436)

- [x] **Portail police judiciaire** : depot securise de PV, photos, videos, metadonnees
- [x] **Interface administration penitentiaire** : reception des mandats, retour etats de detention
- [x] **Interface tresor public** : reception des amendes, retour confirmations de paiement
- [x] **Messagerie securisee inter-services** : echanges entre les 6 services

---

## CE QUI RESTE A FAIRE (optionnel — confort)

### Priorite 9 — Fonctionnalites complementaires non critiques

- [ ] Edition d'un dossier apres creation (infraction, parties, parquet competent)
- [ ] Suppression controlee d'un dossier (admin uniquement, avec confirmation)
- [ ] Export de la liste des dossiers en PDF ou Excel
- [ ] Rapports periodiques automatiques (mensuel, trimestriel, annuel) exportables
- [ ] Tableau de bord statistique accessible au procureur (pas seulement admin)
- [ ] Page de profil utilisateur completement fonctionnelle (modification nom, mot de passe)
- [ ] Integration reelle d'un provider SMS (actuellement stub loggue)
- [ ] Chiffrement au repos (AES) et sauvegardes PCA/PRA (infrastructure)

---

## Commandes a lancer en local

```bash
composer require barryvdh/laravel-dompdf
php artisan migrate
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan db:seed --class=InstitutionSeeder
php artisan config:clear
php artisan cache:clear
```

Configurer les variables `MAIL_*` dans `.env` pour activer l'envoi reel des emails.
MinIO doit etre lance via Docker. Le bucket `parquet` doit exister.
Le `.env` doit avoir `FILESYSTEM_DISK=minio`.

---

## RECAPITULATIF

| Etape / Module                | Intitule                          | Statut    |
|-------------------------------|-----------------------------------|-----------|
| Etape 1                       | Enregistrement du dossier         | Complet   |
| Etape 2                       | Analyse et orientation parquet    | Complet   |
| Etape 3                       | Instruction judiciaire            | Complet   |
| Etape 4                       | Preparation de l'audience         | Complet   |
| Etape 5                       | Audience et decision judiciaire   | Complet   |
| Etape 6                       | Execution des decisions           | Complet   |
| Etape 7                       | Archivage et suivi statistique    | Complet   |
| Recherche & filtrage          | Mots-cles, filtres                | Complet   |
| Notifications                 | Email + stub SMS                  | Complet   |
| Securite / RBAC               | 9 roles, cloisonnement parquet    | Complet   |
| Journal d'audit               | Logs, IP, export CSV              | Complet   |
| Generation PDF                | 6 actes + signatures              | Complet   |
| Portails externes             | PJ, penitentiaire, tresor, messagerie | Complet |
| Confort (priorite 9)          | Edition, exports, profil          | Optionnel |
