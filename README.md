# datocracy — intégration Moodle (thème enfant d'Elan)

Intégration de la maquette Figma « datocracy LMS — Elan Redesign » dans Moodle 5.2.2 avec le thème commercial **Elan** (ThemeForest) et un **thème enfant `datocracy`**, de sorte que les mises à jour d'Elan n'écrasent pas les personnalisations.

> Ce dépôt ne contient **pas** le thème Elan (licence commerciale), ni les logos et photos de la marque, ni aucun identifiant. Voir « Ce qui n'est pas dans le dépôt ».

## Contenu

| Dossier | Rôle |
|---|---|
| `theme/datocracy/` | Thème enfant Moodle (parent : `elan`, puis `boost`). À copier dans `public/theme/datocracy` d'une instance Moodle 5.2. |
| `provisioning/` | Scripts PHP (CLI Moodle) qui ont créé le contenu : blocs de l'accueil, pages, pied de page, blog, langue, fiche du cours. Numérotés dans l'ordre d'exécution. |
| `docs/ETAT.md` | État d'avancement, limites connues, reste à faire, nettoyage de fin de projet. |
| `docs/TEMPS_CLOCKIFY.md` | Estimation du temps passé, par poste, pour la saisie Clockify. |

## Le thème enfant

- `config.php` : `parents = ['elan', 'boost']` ; remplace trois mises en page (`mydashboard`, `mycourses`/`mypublic`, `standard` conditionnelle).
- `lib.php` : assemble le SCSS d'Elan et `scss/datocracy.scss` ; prête au thème enfant les réglages du thème parent (couleurs, logos, pied de page).
- `scss/datocracy.scss` : charte (Montserrat / Open Sans, `#f18619`, `#005a6d`, `#192e4e`), bandeaux de titre, galerie, fiche du cours, écran de connexion scindé, coque de l'espace apprenant.
- `layout/datocracy_my.php` + `templates/dato_my.mustache` : tableau de bord sur mesure (salutation, 4 statistiques, cours en cours, échéances).
- `layout/datocracy_app.php` + `templates/dato_app.mustache` : coque applicative (menu marine) pour Mes cours, profil, calendrier, badges, notes, préférences.
- `lang/en|fr/theme_datocracy.php` : chaînes.

Installation : copier le dossier, puis `php admin/cli/upgrade.php --non-interactive`, `php admin/cli/cfg.php --name=theme --set=datocracy`, `php admin/cli/purge_caches.php`.

## Réglages Moodle modifiés pour que le site public fonctionne

`forcelogin=0`, `enablemyhome=1`, `defaulthomepage=0` (accueil du site public), `registerauth=email` (auto-inscription), `enablemycourses=1`, blogs publics (`bloglevel=5`, capacité `moodle/blog:view`), `supportavailability=2` (formulaire de contact du site), inscription libre activée sur le cours gratuit, langue par défaut `fr` avec pack français installé.

## Ce qui n'est pas dans le dépôt

- Thème **Elan** (à acheter / installer séparément, version « Moodle 5.2.2 » du pack, blocs `elan_*` inclus).
- Logos, formes de marque et photos (kit de marque) : à déposer dans `theme/elan/pix/brand/` sur le serveur ; les scripts et le SCSS y font référence par URL.
- Jetons, clés, mots de passe, adresse du serveur.

## Conventions

- La marque s'écrit toujours **datocracy** (d minuscule).
- Les textes sont ceux de la maquette Figma. Les réponses de la FAQ autres que la première, les témoignages et les pages légales sont des contenus provisoires à faire valider.
