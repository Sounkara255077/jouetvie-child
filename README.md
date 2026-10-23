# 🧸 Jouet&Vie

> **Donner devient aussi simple que jeter.**

Plateforme de dons et d'échange de jouets entre familles de **Schaerbeek**.
Projet réalisé dans le cadre du module **Projet Digital 2 (12E01)** — ISFSC, Écriture multimédia, 2026-2027.

---

## 👥 L'équipe

| Membre | Rôle principal |
|---|---|
| **Sounkara** | Contenus, rédaction, identité visuelle, gestion de projet |
| **Samuella Munyandamutsa** | Intégration WordPress, typographie, mise en page, images |

## 💡 Le concept

Les familles accumulent des jouets que les enfants n'utilisent plus, pendant que d'autres parents cherchent des jouets à petit prix. **Jouet&Vie** les met en relation :

- **Je donne** : je publie une annonce (photo, catégorie, tranche d'âge) et je propose 2 ou 3 créneaux de retrait.
- **Je cherche** : je filtre les annonces, j'ajoute des jouets à mon panier et je choisis un créneau.
- **Objectif écologique** : prolonger la vie des jouets et réduire les déchets.

**Public cible** : les parents de Schaerbeek. **Public secondaire** : tous les habitants de la commune.
**Persona principale** : Camille, 40 ans, cheffe de projet, maman de 4 enfants.

## 🛠️ Outils utilisés

| Outil | Usage |
|---|---|
| **WordPress** + éditeur **Gutenberg** | Construction du site (thème enfant) |
| **Local WP** | Serveur WordPress en local |
| **Git / GitHub** | Versionnement et travail à deux |
| **GitHub Projects** | Suivi des tâches (issues) |
| **VS Code** / **GitHub Desktop** | Édition du code et commits |
| **Figma** | Zoning, wireframes, maquettes |

> Le site est développé **en local uniquement** et présenté devant un jury.

## 📁 Arborescence

```
jouetvie-child/
├── style.css          # En-tête du thème enfant (obligatoire)
├── functions.php      # Chargement des styles et fonctions
├── theme.json         # Couleurs, typographies, espacements (Gutenberg)
├── templates/         # Modèles de pages (block templates)
├── parts/             # Parties de modèles (header, footer)
├── patterns/          # Compositions de blocs réutilisables
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── .gitignore
└── README.md
```

## 📏 Nos conventions

### Branches
- `main` : version stable, **on ne travaille jamais directement dessus**.
- Une branche par tâche, nommée selon son type :
  - `feature/nom-de-la-fonction` → nouvelle fonctionnalité (ex. `feature/page-accueil`)
  - `fix/nom-du-probleme` → correction (ex. `fix/menu-mobile`)
  - `docs/sujet` → documentation (ex. `docs/readme`)
  - `style/sujet` → design, CSS (ex. `style/couleurs`)

### Commits
Messages courts, en français, au présent, précédés du type :
- `feat: ajoute le formulaire de don`
- `fix: corrige l'affichage du panier sur mobile`
- `docs: complète le README`
- `style: applique la palette de couleurs`

### Pull requests
1. Je pousse ma branche et j'ouvre une pull request vers `main`.
2. Je lie l'issue concernée (ex. `Closes #3`).
3. L'autre membre relit, puis on fusionne.
4. Je supprime la branche une fois fusionnée.

### Tableau GitHub Projects
`À faire` → `En cours` → `À vérifier` → `Terminé`

## 📋 Répartition des tâches

| Tâche | Responsable |
|---|---|
| Mise en place du dépôt et du README | Sounkara |
| Installation du thème enfant dans Local WP | Sounkara & Samuella |
| Palette et typographies (`theme.json`) | Sounkara & Samuella |
| Contenus et textes des pages | Sounkara & Samuella |
| Page d'accueil | Sounkara & Samuella |
| Pages Chercher / Panier / Confirmation | Sounkara & Samuella |
| Formulaire « Je donne » | Sounkara & Samuella |
| Images et visuels | Sounkara & Samuella |

*Le détail et l'avancement sont suivis dans les issues et le tableau GitHub Projects.*

## 🚀 Installation en local

1. Installer **Local WP** et créer un site.
2. Installer le thème parent **Twenty Twenty-Five** (Apparence → Thèmes).
3. Cloner ce dépôt dans `wp-content/themes/` du site Local :
   ```bash
   git clone <lien-du-depot> jouetvie-child
   ```
4. Activer **Jouet&Vie (thème enfant)** dans Apparence → Thèmes.
