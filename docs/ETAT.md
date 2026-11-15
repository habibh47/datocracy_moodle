# État d'avancement (au 15/11/2026)

## Fait
- Sauvegarde, installation d'Elan 2.3 (Moodle 5.2.2), charte (polices, couleurs, logos, pied de page, réseaux sociaux).
- Domaine de test en HTTPS (reverse proxy nginx dédié, certificat Let's Encrypt, `wwwroot` mis à jour).
- Accueil en 15 blocs Elan, texte conforme au Figma.
- Pages de contenu : À propos, Formateurs, FAQ, Contact, Histoires de réussite, Témoignages, Galerie, Conditions générales, Confidentialité, Cookies, fiche du cours.
- Blog (3 articles de démonstration), formulaire de contact du site, auto-inscription, pack de langue français.
- Écran de connexion / inscription / mot de passe oublié scindé (CSS).
- Thème enfant `datocracy` ; tableau de bord sur mesure ; coque applicative pour l'espace apprenant (écrite, déploiement et contrôle visuel en cours).
- Trois relectures Q&A visuelles par agent (accueil, pages de contenu, pages du pied de page et pages natives).

## Écarts connus avec le Figma (limites d'Elan ou de Moodle)
- Mosaïque de 4 photos de la bannière d'accueil, carte de cours aux étiquettes Moodle, carrousel de témoignages à 2 cartes, FAQ en 2 colonnes.
- Boutons de connexion Google / LinkedIn / Apple : nécessitent des applications OAuth.
- Liste des cours : pas de filtres ni de tri ; second cours « Bientôt » absent.
- Onglets de la fiche du cours : liens d'ancre uniquement.
- Statistiques du tableau de bord : « Activités terminées » et « Badges obtenus » remplacent « Heures apprises » et « Certificats » (non calculables).
- Lecteur de cours : contenu SCORM standard du cours d'origine, non reproduit.
- Titres d'onglet produits par Moodle (« datocracy: … | datocracy »).

## Reste à faire
1. Contrôle visuel de Mes cours, profil, calendrier (devoirs), badges (certificats) dans la coque applicative.
2. Lecteur de cours.
3. Redirection des cartes de cours vers la fiche du Figma ; relecture Q&A finale.
4. URL de la vidéo de la section « Histoires d'apprenants » ; second cours « Bientôt ».

## À valider par le client
- Réponses de la FAQ (questions 2, 3, 6 et 7), témoignages, pages légales (texte du Figma, mentionne des remboursements alors que le cours est gratuit).
- Articles de blog de démonstration (auteur et contenus).

## Nettoyage de fin de projet
- Supprimer le compte de test `test.apprenant`.
- Révoquer le jeton d'accès personnel Figma utilisé pour lire la maquette.
- Retirer de `authorized_keys` la clé de travail ajoutée sur le serveur.
- Changer le mot de passe administrateur initial.
- Vider le réglage SCSS de repli `theme_elan/scss` en base une fois le thème enfant validé.
