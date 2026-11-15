# Estimation du temps — saisie Clockify

Période : 13 au 15 novembre 2026. Projet réalisé avec l'assistance d'un agent IA (Claude Code) qui a écrit et exécuté la quasi-totalité du code, des scripts et des vérifications visuelles.

Les durées ci-dessous sont des **estimations**, pas des temps mesurés. Elles distinguent :
- l'**effort de référence sans IA** : ce qu'un développeur Moodle senior aurait mis à réaliser seul le même travail ;
- le **temps humain avec IA** : le temps de Habib (cadrage, accès, décisions, relecture des captures et des rapports Q&A, actions chez des tiers comme le DNS, validation). C'est cette colonne qu'il faut saisir dans Clockify, après ajustement avec le temps réellement passé.

| # | Poste (description Clockify suggérée) | Référence sans IA | Temps humain avec IA |
|---|---|---|---|
| 1 | Cadrage : accès serveur et Figma, choix d'approche | 3 h 00 | 1 h 00 |
| 2 | Installation d'Elan, charte graphique, pied de page (validations) | 4 h 00 | 1 h 00 |
| 3 | Domaine de test : DNS chez le registrar, validation HTTPS | 2 h 00 | 0 h 30 |
| 4 | Page d'accueil : relecture des rendus, retours | 8 h 00 | 2 h 00 |
| 5 | Pages À propos, Formateurs, FAQ, Contact, Histoires, Témoignages, Galerie : relecture | 5 h 30 | 1 h 30 |
| 6 | Pages légales, blog, contact, inscription, langue : décisions et validation | 3 h 30 | 1 h 00 |
| 7 | Fiche du cours : relecture | 2 h 30 | 0 h 30 |
| 8 | Écran de connexion scindé : relecture | 3 h 30 | 1 h 00 |
| 9 | Thème enfant `datocracy` : choix d'architecture, validation | 3 h 00 | 0 h 45 |
| 10 | Tableau de bord et espace apprenant : relecture, arbitrages | 5 h 00 | 1 h 30 |
| 11 | Relectures Q&A (3 passes) : lecture des rapports, arbitrages | 4 h 00 | 1 h 30 |
| 12 | Compte de test, documentation, dépôt Git, suivi du temps | 2 h 00 | 1 h 00 |
| | **Total estimé** | **46 h 00** | **13 h 15** |

Fourchette conseillée pour le temps humain avec IA : **10 h à 16 h**. Le temps d'exécution de l'agent (écriture des scripts, captures, déploiements, attente de compilation des styles) n'est pas compté, car il ne mobilise pas Habib.

À savoir pour la saisie : l'historique horodaté de la session Claude Code permet de recouper ces chiffres avec le temps réellement passé devant l'écran.

Non inclus : validation des contenus par le client, branchement OAuth (Google, LinkedIn, Apple), lecteur de cours, vidéo des témoignages, second cours.

---

## Recoupement avec l'historique horodaté de la session (15/11/2026)

Méthode : la transcription de la session Claude Code a été dépouillée pour relever l'heure de chaque message réel de Habib (31 messages) et l'heure de la dernière sortie de l'IA qui le précède. Le **temps humain actif** d'un message est le délai entre la fin du travail de l'IA et le message (lecture, décision, saisie, actions chez des tiers), **plafonné à 10 minutes** ; au-delà, c'est une pause ou une attente. Premier message : 3 minutes de saisie ajoutées. Heures en UTC (Dakar = UTC+0).

Jours : **13/11** et **15/11**. Aucune activité le **14/11**.

### Temps humain actif par poste (plafond 10 min)

| Poste | Messages pris en compte (UTC) | Actif |
|---|---|---|
| Cadrage : accès serveur et Figma | 13/11 13:40–13:59 et 14:17–14:31 | 0 h 33 |
| Installation Elan, charte | 13/11 14:01–14:04, 16:28–16:56 | 0 h 21 |
| Domaine de test (DNS, HTTPS) | 13/11 19:33 | 0 h 10 |
| Accueil | 13/11 19:49–20:16, 21:21 | 0 h 12 |
| Pages de contenu | 13/11 22:23 (part) | 0 h 00 |
| Pages légales, blog, langue | 13/11 22:23 (part) | 0 h 01 |
| Fiche du cours | 13/11 23:27 (part) | 0 h 05 |
| Connexion scindée | 13/11 22:55, 23:05 | 0 h 12 |
| Thème enfant | 15/11 21:05 | 0 h 02 |
| Tableau de bord, espace apprenant | 15/11 21:41, 22:50 | 0 h 12 |
| Relectures Q&A | 13/11 21:50, 22:03 | 0 h 11 |
| Compte de test, documentation, Git | 13/11 23:27 (part) ; 15/11 20:24, 23:06, 23:11 | 0 h 08 |
| **Total actif** | | **2 h 07** |

Par jour : **13/11 = 1 h 50** ; **15/11 = 0 h 17**. Fourchette selon le plafond : 1 h 24 (plafond 5 min) à 2 h 40 (plafond 15 min).

### Présence devant la session (borne haute)

Si Habib a suivi l'écran pendant les exécutions de l'IA, les plages continues (écart entre messages inférieur à 30 min) sont : 13/11 13:40–14:45 (1 h 05), 16:28–16:56 (0 h 28), 19:33–environ 23:30 (environ 4 h) ; 15/11 20:24–23:12 (2 h 48). Total d'environ **8 h 20**.

### Conséquence sur la saisie

L'estimation de 13 h 15 donnée plus haut dépasse la présence maximale observée (8 h 20) : elle est trop haute pour du temps réel. À saisir dans Clockify : entre **2 h 07** (temps strictement actif) et **8 h 20** (présence totale), selon ce que Habib décide de compter. La référence sans IA (46 h) reste l'effort équivalent d'un développeur seul.
