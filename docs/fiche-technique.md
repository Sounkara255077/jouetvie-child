# Fiche technique — Jouet&Vie

> **Projet Digital 2 (12E01) — Éco-challenge**
> Binôme : Sounkara Camara & Samuella Munyandamutsa — EMU2, ISFSC
> Slogan : *« Donner devient aussi simple que jeter »*
> Version : 0.1 (novembre 2026) — document vivant, mis à jour avec les wireframes, maquettes et le design system.

---

## 0. Le projet en une phrase

Jouet&Vie est une plateforme de **dons et d'échange de jouets entre familles de Schaerbeek** : une personne publie un jouet dont elle n'a plus besoin, une autre le réserve et choisit un créneau de retrait. Objectif éco-challenge : **prolonger la vie des jouets et éviter qu'ils finissent à la poubelle.**

- **Public principal :** parents schaerbeekois
- **Public secondaire :** tous les habitants de Schaerbeek
- **Persona :** Camille, 40 ans, cheffe de projet, maman de 4 enfants (2 à 12 ans). Elle veut donner les jouets en trop et trouver des jouets gratuits pour ses aînés.

---

## 1. Pages à produire

**QUOI → COMMENT → POURQUOI** : pages créées dans le **back-end WordPress** → chacune répond à un **besoin utilisateur**.

| Page | Contenu principal | Besoin utilisateur (pourquoi) |
|---|---|---|
| **Accueil** | Texte de présentation, visuel/vidéo hero, boutons « Je cherche » / « Je donne », compteur d'impact | Comprendre le concept en quelques secondes et choisir son parcours |
| **Chercher** | Barre de recherche, filtres (catégorie, tranche d'âge), liste de cartes jouets | Trouver rapidement un jouet adapté à l'âge de son enfant |
| **Chercher — variante « Aucun résultat »** | Message clair + suggestion de modifier les filtres | Ne pas rester bloquée face à une page vide |
| **Fiche jouet** *(à valider dans les wireframes)* | Photo, description, catégorie, âge, état, créneaux de retrait proposés | Avoir toutes les infos avant de réserver |
| **Panier** | Jouets réservés, rendez-vous de retrait (date, heure, adresse), bouton « Confirmer » | Vérifier sa sélection et son rendez-vous avant de s'engager |
| **Confirmation** | Récapitulatif + message de remerciement | Être rassurée que la réservation est bien prise en compte |
| **Favoris** | Jouets mis de côté, accès direct au panier | Garder une idée pour plus tard (autre chemin vers le panier) |
| **Publier** | Formulaire d'annonce (photo, titre, catégorie, âge, état, 2–3 créneaux) | Donner un jouet en quelques minutes |
| **Messages** | Échanges entre donneuse et personne qui réserve | Se mettre d'accord sans partager son numéro |
| **Calendrier** | Liste des rendez-vous de retrait à venir | Ne pas oublier un retrait |
| **Profil** | Infos personnelles, annonces publiées, jouets donnés/reçus | Gérer son activité et voir sa contribution |
| **Connexion / Inscription** | Formulaires d'accès au compte | Sécuriser les échanges entre habitants |

**Pourquoi le back-end WordPress ?** Le CMS permet de créer et organiser les pages sans coder chaque gabarit, de gérer les utilisateurs (comptes, rôles) et de séparer contenu et mise en forme. C'est l'outil imposé par le cours, et il correspond à un usage professionnel réel.

---

## 2. Sections et composants

**COMMENT :** éditeur **Gutenberg** (blocs et compositions/patterns) + **thème enfant** (`jouetvie-child`) d'un thème parent basé sur les blocs *(thème parent à confirmer, ex. Twenty Twenty-Five)*.
**POURQUOI :** adaptation aux supports — **mobile d'abord**, puis tablette et ordinateur.

| Composant | Où ? | Réalisation | Adaptation aux supports |
|---|---|---|---|
| **En-tête** : ☰ menu · logo · 👤 connexion · 🧺 panier avec compteur | Toutes les pages | Template part `header.html` du thème enfant | Mobile : icônes seules. Ordinateur : menu déployé en texte |
| **Barre du bas** : Calendrier · Favoris · Publier · Messages · Profil | Toutes les pages (mobile) | Template part `footer` + CSS | Visible sur mobile/tablette, remplacée par le menu principal sur ordinateur |
| **Hero** (texte + vidéo/image + 2 boutons) | Accueil | Bloc Couverture + Boutons, enregistré en pattern | Vidéo remplacée par une image sur mobile si trop lourde |
| **Carte jouet** (photo, titre, âge, catégorie, ♡ favori) | Chercher, Favoris, Profil | Pattern réutilisable / bloc Query Loop | 1 colonne mobile, 2 tablette, 3–4 ordinateur |
| **Barre de recherche + filtres** | Chercher | Bloc Recherche + filtres en JavaScript | Filtres repliables sur mobile |
| **Formulaire d'annonce** | Publier | Formulaire *(extension à choisir : WP User Frontend ou formulaire maison)* | Champs empilés en pleine largeur sur mobile |
| **Récapitulatif rendez-vous** | Panier, Confirmation | Groupe de blocs + pattern | Empilé sur mobile, en colonnes sur ordinateur |
| **Compteur d'impact** | Accueil, Profil | Bloc HTML personnalisé + JavaScript | Chiffres empilés sur mobile |
| **Messages système** (confirmation, erreur, aucun résultat) | Plusieurs pages | Pattern + classes CSS d'état | Identique sur tous les supports |

**Pourquoi un thème enfant ?** Il permet de personnaliser le thème (couleurs, typos, gabarits, CSS, JS) **sans perdre nos modifications lors des mises à jour** du thème parent. **Pourquoi Gutenberg ?** Blocs natifs, légers, réutilisables sous forme de patterns : on construit un composant une fois et on le réutilise partout, ce qui garantit la cohérence visuelle.

---

## 3. Contenus, médias et réglages du thème

**COMMENT :** **theme.json** (réglages globaux) + **style.css** du thème enfant (détails).
**POURQUOI :** des **interactions attendues** claires et une identité cohérente.

### Réglages theme.json (design tokens)

| Réglage | Valeurs |
|---|---|
| **Couleurs** | Crème `#FFF6E5` · Encre `#1F1640` · Orange `#FF6B2C` · Jaune `#FFC93C` · Vert `#2E9E6A` · Rouge `#D93F3F` (fond des pages : jaune clair) |
| **Typographies** | Titres : *Obviously Black* · Texte : *Proxima Soft Regular* *(vérifier la licence web ; prévoir une alternative Google Fonts si besoin)* |
| **Espacements** | Échelle à définir avec le design system (ex. 8 / 16 / 24 / 32 / 48 px) |
| **Logo** | Monogramme « jv », lettres bleu Encre, cœur orange à la place du point du j |

### Rôle des couleurs dans les interactions

- **Orange** : actions principales (Je cherche, Réserver, Confirmer, Publier)
- **Vert** : succès, confirmation, impact positif (compteur)
- **Rouge** : erreurs, champ manquant, annulation
- **Encre** : textes et icônes

### Médias

- Photos des jouets publiées par les utilisateurs (format carré, compressées)
- Vidéo en boucle dans le hero de l'accueil *(générée par IA, essai Adobe Firefly)*, muette, avec image de remplacement
- Icônes simples pour la navigation (bonhomme, panier, cœur, calendrier…)

**Pourquoi theme.json ?** On définit les couleurs, typos et espacements **une seule fois** et ils sont disponibles partout dans l'éditeur. Samuella et moi utilisons donc exactement les mêmes valeurs, sans « couleur au hasard ».

---

## 4. Gestion d'états

**COMMENT :** **JavaScript** (fichier `assets/js/main.js` chargé par le thème enfant).
**POURQUOI :** l'interface doit **transmettre une information** à l'utilisatrice à chaque action.

| État | Déclencheur | Réaction de l'interface | Information transmise |
|---|---|---|---|
| Menu ouvert / fermé | Clic sur ☰ | Le menu s'ouvre/se ferme, `aria-expanded` mis à jour | « Voici les sections du site » |
| Filtre actif | Clic sur une catégorie / tranche d'âge | Le filtre change de couleur, la liste se met à jour | « Tu vois uniquement les jouets 3–5 ans » |
| Aucun résultat | Recherche sans correspondance | Affichage de la variante « Aucun résultat » | « Essaie d'autres filtres » |
| Favori ajouté / retiré | Clic sur ♡ | Cœur plein/vide + petit message | « Ajouté à tes favoris » |
| Compteur du panier | Ajout d'un jouet | Le chiffre sur 🧺 augmente | « Tu as 2 jouets dans ton panier » |
| Créneau sélectionné | Choix d'un créneau de retrait | Créneau surligné, bouton Confirmer activé | « Rendez-vous choisi : mardi 18 h » |
| Formulaire incomplet | Envoi avec champ manquant | Champ bordé de rouge + message | « Ajoute une photo du jouet » |
| Confirmation | Clic sur Confirmer | Redirection vers la page Confirmation | « Ta réservation est enregistrée » |
| Chargement | Récupération des annonces | Indicateur de chargement | « Patiente un instant » |

**Pourquoi JavaScript ?** Ces changements se font **sans recharger la page**, ce qui rend l'expérience fluide sur mobile. Gutenberg seul crée des pages statiques ; JavaScript ajoute le comportement.

---

## 5. Données dynamiques

**COMMENT :** type de contenu personnalisé **« annonce »** + **API REST de WordPress** + **Contrat.json**.
**POURQUOI :** créer de la **valeur pour l'éco-challenge**.

### Structure d'une annonce (Contrat.json)

Le fichier `docs/contrat.json` décrit la forme d'une annonce. C'est le « contrat » commun entre le formulaire, l'affichage et la fonction IA : tout le monde parle des mêmes champs.

```json
{
  "annonce": {
    "id": "number",
    "titre": "string",
    "description": "string",
    "photo": "url",
    "categorie": ["Peluches", "Jeux de société", "Livres", "Construction", "Extérieur", "Éveil", "Autre"],
    "tranche_age": ["0-2", "3-5", "6-8", "9-12"],
    "etat": ["Comme neuf", "Bon état", "Usé mais fonctionnel"],
    "quartier": "string",
    "creneaux": ["datetime"],
    "statut": ["disponible", "réservé", "donné"],
    "poids_estime_kg": "number",
    "auteur_id": "number"
  }
}
```

### Idées de données / API

1. **API REST WordPress** (`/wp-json/wp/v2/annonce`) : la page Chercher récupère les annonces en JavaScript et les filtre sans rechargement.
2. **Compteur d'impact** : à chaque jouet passé au statut « donné », on additionne `poids_estime_kg`. Affichage : *« 124 jouets sauvés · 87 kg de déchets évités à Schaerbeek »*.
3. *(Optionnel)* **Carte des points de retrait** avec Leaflet + OpenStreetMap (gratuit), en n'affichant que le **quartier**, jamais l'adresse exacte.

**Valeur pour l'éco-challenge :** le compteur rend l'impact **visible et motivant** ; chaque don devient un geste concret mesuré. C'est aussi un argument fort devant le jury.

---

## 6. Fonction « intelligente » (IA)

**COMMENT :** IA + Contrat.json + extension *(choix final à valider avec Mme Bouchat)*.
**POURQUOI :** ce qu'elle apporte **vraiment**.

### Idée principale : assistant de publication d'annonce

La donneuse ajoute **une photo** du jouet → l'IA propose automatiquement :
- un **titre** et une **courte description**,
- la **catégorie** et la **tranche d'âge**,
- en respectant les valeurs autorisées du **Contrat.json**.

La personne relit, corrige si besoin, puis publie.

**Ce que ça apporte vraiment :**
- publier en **moins d'une minute** → moins de friction, donc plus de dons (cœur du slogan « aussi simple que jeter ») ;
- des annonces **homogènes et bien classées** → les filtres de la page Chercher fonctionnent mieux ;
- l'utilisatrice garde le contrôle : l'IA **suggère**, elle ne publie pas seule.

### Idée alternative : chatbot d'aide (Tidio, offre gratuite)

Répond aux questions fréquentes (« Comment réserver ? », « Que faire si je ne peux pas venir au rendez-vous ? »). Plus simple à intégrer, mais apporte moins de valeur propre au projet.

---

## 7. Récapitulatif des outils et justification

| Outil | Rôle | Pourquoi celui-ci ? |
|---|---|---|
| **WordPress** (en local) | CMS, pages, utilisateurs | Imposé par le cours, standard professionnel |
| **Gutenberg** | Construction des pages et composants | Imposé, natif, léger, patterns réutilisables |
| **Thème enfant `jouetvie-child`** | Personnalisation | Modifications protégées des mises à jour |
| **theme.json** | Couleurs, typos, espacements | Une seule source de vérité pour le design |
| **CSS** (`style.css`) | Détails visuels, responsive | Ce que theme.json ne couvre pas |
| **JavaScript** | États de l'interface, filtres | Interactions sans rechargement |
| **API REST WordPress** | Récupérer les annonces | Intégrée à WordPress, rien à installer |
| **Contrat.json** | Structure des données | Langage commun entre front, données et IA |
| **Extension formulaire** *(à choisir)* | Publication d'annonces côté visiteur | Évite de coder l'envoi de fichiers et la sécurité |
| **Outil IA** *(à valider)* | Aide à la publication | Réduit l'effort de don |
| **GitHub** | Versionnage, travail à deux | Historique, pull requests, relecture mutuelle |
| **Figma** | Wireframes, maquettes, design system | Base visuelle avant l'intégration |
| **Teams Planner** | Planning | Suivi partagé avec le professeur |

---

## 8. Contraintes

- **Technique :** WordPress + Gutenberg imposés (pas de Divi). Thème enfant obligatoire.
- **Hébergement :** site développé et présenté **en local** (Local / XAMPP), pas de mise en ligne → fonctionnel pour la démonstration devant le jury.
- **Budget :** zéro euro → uniquement des extensions et services **gratuits** (attention aux limites des offres gratuites, ex. 200 conversations/mois pour Tidio).
- **Périmètre :** une seule fonctionnalité centrale (le don de jouets), suite au retour du professeur. Pas de calendrier de réservation complexe : la donneuse propose 2–3 créneaux, l'autre personne en choisit un.
- **Langue :** site en **français uniquement** (FR/NL seulement si mise en ligne réelle).
- **Supports :** **mobile d'abord**, puis tablette et ordinateur.
- **Vie privée (RGPD) :** pas d'adresse exacte publique, seulement le quartier ; adresse communiquée uniquement après réservation. Pas de données d'enfants.
- **Accessibilité :** contrastes suffisants (vérifier l'orange sur crème), textes alternatifs sur les photos, navigation au clavier, `aria-expanded` sur le menu.
- **Typographies :** vérifier les licences web d'Obviously et Proxima Soft (polices payantes) ; prévoir une alternative gratuite.
- **Temps et équipe :** binôme, calendrier du quadrimestre, défense devant jury.

---

## 9. Suivi des versions

| Version | Date | Changements |
|---|---|---|
| 0.1 | nov. 2026 | Première version de la fiche technique |
