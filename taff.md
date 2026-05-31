# Taff - Systeme Parquet

---

## ETAT D'AVANCEMENT — mis a jour le 2026-05-31

Ce fichier retrace tout ce qui a ete implemente et tout ce qui reste a faire
pour couvrir l'integralite du cahier des charges `gestion_dossiers_parquet.md`.

---

## CE QUI EST IMPLEMENTE

### Infrastructure & securite de base

- Authentification (login / logout)
- Changement de mot de passe force au premier login (middleware `force.password.change`)
- RBAC avec Spatie `laravel-permission` : roles `admin`, `greffier`, `procureur`, `juge`
- Vues et layouts separes par role (admin, greffier, procureur, juge)
- Stockage fichiers PDF dans MinIO (S3-compatible) via Docker
- Compatibilite de lecture avec les anciens fichiers stockes en `public`
- Configuration Docker Compose avec MinIO

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

---

### Etape 2 — Analyse et decision d'orientation (PARTIEL)

- Formulaire d'orientation du procureur sur chaque dossier
- 4 types d'orientation implementes :
  - Classement sans suite → statut `Classe`
  - Citation directe → statut `Oriente`
  - Comparution immediate → statut `Oriente`
  - Requisitoire introductif → statut `En instruction`
- Motif obligatoire + date de decision
- Statut du dossier mis a jour automatiquement
- Bouton "Decider orientation" ou "Modifier l'orientation" sur la page detail
- Historique horodate logue dans `dossier_historique`

Manquant dans cette etape :
- Mediation penale (cite explicitement dans le cahier p. 321)
- Renvoi administratif (cite explicitement dans le cahier p. 321)
- Generation d'un acte PDF signe (modele pre-rempli)

---

### Traçabilite transversale (COMPLET)

- Table `dossier_historique` : chaque action enregistree avec user, date, action, detail
- Affichage de l'historique sur la page detail du dossier
- Toutes les etapes tracees : orientation, audience, PV, decision, execution, archivage

---

### Etape 4 — Preparation de l'audience (COMPLET)

- Creation d'une audience : date, salle, type (correctionnelle, criminelle, sociale, refere)
- Selection multi-dossiers pour le role de l'audience
- Lien many-to-many Dossier — Audience via table pivot `dossier_audience`
- Liste des audiences par role : greffier, juge, procureur
- Historique logue lors de la planification
- Statut dossier mis a jour vers `Oriente` si encore `En cours`

---

### Etape 5 — Audience et decision judiciaire (COMPLET)

- Saisie du PV d'audience par le greffier (modifiable)
- Formulaire de decision judiciaire par le juge, par dossier inscrit :
  - Types : jugement, ordonnance, arret
  - Contenu du dispositif
  - Date de decision
  - Signature (nom du juge connecte)
- Decision modifiable (updateOrCreate)
- Statut dossier → `Juge` automatiquement
- Historique logue

---

### Etape 6 — Execution des decisions (COMPLET)

- Creation d'une execution par decision rendue :
  - Type de peine : privative de liberte, pecuniaire, complementaire
  - Institution executante : Administration penitentiaire, Tresor public, Huissiers
- Seeder `InstitutionSeeder` : 3 institutions par defaut
- Mise a jour du statut d'execution : en_cours, executee, non_executee
- Date d'execution effective enregistree
- Cloture automatique du dossier (statut `Execute`) quand toutes les peines sont executees
- Tableau de bord des executions avec 3 compteurs
- Historique logue a chaque changement

---

### Etape 7 — Archivage et suivi statistique (COMPLET)

- Archivage controle des dossiers (statuts eligibles : Execute, Classe, Juge)
- Motif d'archivage obligatoire + date
- Action irreversible : statut → `Archive`
- Liste des archives consultable (greffier)
- Tableau de bord statistique (admin) :
  - 8 KPI cards : total, en cours, juge, execute, classe, archive, audiences, decisions
  - Graphe bar : enregistrements sur les 6 derniers mois (Chart.js)
  - Graphe doughnut : repartition par statut
  - Tableau : dossiers par registre avec barre de progression
  - Progress bars : repartition des types d'orientation
  - Graphe doughnut : taux d'execution
  - Tableau des 10 derniers dossiers enregistres
- Dashboards accueil mis a jour avec vraies donnees :
  - Admin : KPIs globaux + derniers dossiers
  - Procureur : compteurs + derniers dossiers + recap statuts
  - Juge : compteurs + prochaines audiences
  - Greffier : ses dossiers + executions en cours

---

### Commandes a lancer en local

```bash
php artisan migrate
php artisan db:seed --class=InstitutionSeeder
php artisan config:clear
php artisan cache:clear
```

MinIO doit etre lance via Docker. Le bucket `parquet` doit exister.
Le `.env` doit avoir `FILESYSTEM_DISK=minio`.

---

## CE QUI RESTE A IMPLEMENTER

Liste exhaustive issue du cahier des charges `gestion_dossiers_parquet.md`,
classee par priorite d'implementation.

---

### Priorite 1 — Etape 3 : Instruction judiciaire (NON IMPLEMENTE)

Ref. cahier : section "Etape 3 : Instruction judiciaire et suivi" (pp. 333–348)

- [ ] Module de saisine du juge d'instruction (transmission du requisitoire introductif)
- [ ] Suivi des actes d'instruction : commissions rogatoires, expertises, auditions
- [ ] Calendrier des mesures avec alertes automatiques sur les delais de detention provisoire
- [ ] Gestion des demandes de mise en liberte et prolongations motivees
- [ ] Remontees de la police judiciaire (execution des actes, rapports)
- [ ] Transmission a la chambre d'accusation en cas de recours
- [ ] Messagerie interne securisee entre parquet, juge et police judiciaire

---

### Priorite 2 — Completion de l'etape 2 (orientation incomplète)

Ref. cahier : section "Etape 2 : Analyse et decision d'orientation" (pp. 316–329)

- [ ] Ajouter **mediation penale** comme type d'orientation (p. 321)
- [ ] Ajouter **renvoi administratif** comme type d'orientation (p. 321)
- [ ] Generation d'un acte PDF signe electroniquement (modele pre-rempli) (p. 322)
- [ ] Upload possible d'un acte signe par le procureur

---

### Priorite 3 — Recherche et filtrage

Ref. cahier : "possibilite de recherche par mots-cles" (p. 263)

- [ ] Moteur de recherche dans la liste des dossiers (numero, partie, infraction, date, statut)
- [ ] Filtres par registre, statut, periode sur toutes les listes
- [ ] Recherche dans les archives par mots-cles

---

### Priorite 4 — Notifications electroniques

Ref. cahier : "convocations envoyees par notification electronique (email, SMS)" (pp. 250, 271)

- [ ] Envoi d'email de convocation aux parties lors de la planification d'une audience
- [ ] Notification email au procureur lors de l'enregistrement d'un nouveau dossier
- [ ] Notification email au greffier lors d'une decision d'orientation
- [ ] Notification aux institutions lors de la creation d'une execution
- [ ] Portail de notification electronique (SMS si integration operateur)

---

### Priorite 5 — RBAC complet

Ref. cahier : "profils Parquet, Greffe, PJ, Huissier, Penitentiaire, Tresor, Juridictions, Administration" (p. 424)

- [ ] Role **Substitut** : acces aux dossiers du procureur, saisie d'orientation
- [ ] Role **Police judiciaire** : depot de PV et pieces, mise a jour etat enquete
- [ ] Role **Huissier** : acces restreint aux convocations, retour de statut de signification
- [ ] Role **Administration penitentiaire** : reception des mandats, retour d'etat de detention
- [ ] Role **Tresor public** : reception des amendes, confirmation de paiement
- [ ] Cloisonnement par parquet (un utilisateur ne voit que les dossiers de son parquet)

---

### Priorite 6 — Journal d'audit

Ref. cahier : "Journalisation complete : horodatage, adresse IP, action, objet, version de document" (p. 425)

- [ ] Table `audit_log` : connexions avec IP, user-agent, date
- [ ] Enregistrement de chaque action sensible (creation, modification, suppression, consultation)
- [ ] Interface de consultation du journal d'audit (admin uniquement)
- [ ] Export du journal en CSV ou PDF

---

### Priorite 7 — Generation d'actes PDF

Ref. cahier : "Template d'actes : citations, requisitoires, mandats, jugements, ordonnances, notifications" (p. 443)

- [ ] Modele PDF de **citation directe** (convocation du prevenu)
- [ ] Modele PDF de **requisitoire introductif**
- [ ] Modele PDF de **mandat de depot ou d'arret**
- [ ] Modele PDF de **jugement** (dispositif formate)
- [ ] Modele PDF d'**ordonnance**
- [ ] Modele PDF de **notification de decision** aux parties
- [ ] Zone de signature electronique sur les actes

---

### Priorite 8 — Portails externes

Ref. cahier : section "Integrations recommandees" (pp. 432–436)

- [ ] **Portail police judiciaire** : depot securise de PV, photos, videos, metadonnees
- [ ] **Interface administration penitentiaire** : transmission electronique des mandats, retour etats de detention
- [ ] **Interface tresor public** : transmission des amendes, retour confirmations de paiement
- [ ] **Messagerie securisee inter-services** : echanges entre juridictions

---

### Priorite 9 — Fonctionnalites complementaires

- [ ] Edition d'un dossier apres creation (infraction, parties, parquet competent)
- [ ] Suppression controlee d'un dossier (admin uniquement, avec confirmation)
- [ ] Export de la liste des dossiers en PDF ou Excel
- [ ] Rapports periodiques automatiques (mensuel, trimestriel, annuel) exportables
- [ ] Tableau de bord statistique accessible au procureur (pas seulement admin)
- [ ] Gestion multi-parquet (cloisonnement par juridiction)
- [ ] Page de profil utilisateur completement fonctionnelle (modification nom, mot de passe)

---

## RECAPITULATIF

| Etape cahier                  | Intitule                          | Statut                          |
|-------------------------------|-----------------------------------|---------------------------------|
| Etape 1                       | Enregistrement du dossier         | Complet                         |
| Etape 2                       | Analyse et orientation parquet    | Partiel (4/6 types, sans PDF)   |
| Etape 3                       | Instruction judiciaire            | Non implemente                  |
| Etape 4                       | Preparation de l'audience         | Complet                         |
| Etape 5                       | Audience et decision judiciaire   | Complet                         |
| Etape 6                       | Execution des decisions           | Complet                         |
| Etape 7                       | Archivage et suivi statistique    | Complet                         |
| Securite / RBAC               | Roles, audit, chiffrement         | Partiel (4 roles sur 9)         |
| Notifications                 | Email, SMS, convocations          | Non implemente                  |
| Generation PDF                | Actes, mandats, jugements         | Non implemente                  |
| Portails externes             | PJ, penitentiaire, tresor         | Non implemente                  |
