# Comparatif LearnWorlds / Moodle datocracy et proposition de roadmap (20/11/2026)

## Sources et limites
- **LearnWorlds** : arborescence complète du menu du tableau de bord auteur (offre « Learning Center »), relevée dans le navigateur. Le contenu des écrans n'a pas pu être lu (affichage dans un cadre non accessible, captures impossibles) : les fonctions sont donc comparées **par leur intitulé**, sans avoir vérifié leur usage réel ni leur paramétrage chez datocracy.
- **Cahier des charges de refonte** (225 h, 6 sprints) : plugins, développements et risques prévus.
- **Moodle datocracy** : état de staging, voir `docs/ETAT.md`. Le cahier des charges cite Moodle 4.5 et un thème acheté ; la réalisation utilise **Moodle 5.2.2 et le thème Elan**.
- Les capacités de Moodle indiquées « natif » ou « plugin » viennent de la connaissance générale de la plateforme et sont **à confirmer en recette**. Les heures sont des ordres de grandeur, non validés.

## 1. Comparatif par domaine

Statut sur staging : **Fait** · **Partiel** · **À faire** · **Hors périmètre** (non pertinent pour datocracy aujourd'hui).

### Cours et programmes
| LearnWorlds | Moodle | Statut | Remarque |
|---|---|---|---|
| Courses (éditeur de cours) | Cours, sections, activités | Fait | Contenu actuel = paquet SCORM d'origine, lecteur datocracy autour |
| Learning programs (parcours) | Plans d'apprentissage / compétences, ou parcours par catégories et restrictions | À faire | Utile dès le 2e cours (« MPME ») |
| Course hub (catalogue) | Liste des cours Elan | Partiel | Pas de filtres ni de tri |
| Gradebook | Carnet de notes natif | Fait (non habillé) | Page native Moodle |
| Certificates | Plugin Custom Certificate | À faire | Bloquant pour la promesse « certificat » ; le tableau de bord affiche des badges à la place |
| Review center | Devoirs et correction manuelle natifs | Non vérifié | |
| Question banks | Banque de questions native | Fait (natif) | |
| Course forms | Activité Feedback | Non vérifié | |

### Site web
| LearnWorlds | Moodle | Statut | Remarque |
|---|---|---|---|
| Design, thèmes, navigation | Elan + thème enfant, menu bilingue | Fait | FR/EN, mobile revu |
| Blog | Blog Elan | Fait | 3 articles de démonstration |
| Popups, Funnels | Aucun équivalent natif | Hors périmètre | À reconsidérer pour la conversion |

### Utilisateurs
| LearnWorlds | Moodle | Statut | Remarque |
|---|---|---|---|
| All users, User roles | Utilisateurs et rôles natifs | Fait (natif) | |
| User groups | Cohortes et groupes | À faire (configuration) | Prévu par le cahier des charges pour les ONG |
| User fields | Champs de profil personnalisés | À faire | Base du « secteur d'activité » |
| Approvals | Inscription avec approbation (plugin à confirmer) | À faire | |
| Tags | Étiquettes natives | Non vérifié | |
| Leads | Aucun équivalent | À faire | Collecte de contacts avant inscription |
| Multiple seats | Aucun équivalent direct | À faire | Licences par organisation, pertinent pour ONG et institutions |
| Automations | Synchronisation de cohortes, règles par plugin | À faire | |
| Inscription et confirmation par e-mail | Auto-inscription + SMTP | **Fait (20/11)** | Envoi testé de bout en bout ; réception en boîte à confirmer |
| Connexion Google / LinkedIn | OAuth2 | À faire | Boutons du Figma, applications OAuth à créer par le client |

### Communication
| LearnWorlds | Moodle | Statut | Remarque |
|---|---|---|---|
| Community | Forums | À faire | Expérience à habiller |
| Inbox | Messagerie interne | Fait (natif) | |
| Mass emails | Message groupé par cohorte (plugin à confirmer) | À faire | |
| Push notifications | Application Moodle | Hors périmètre pour l'instant | |
| School emails (modèles) | Chaînes de langue personnalisées | À faire | E-mails d'inscription à habiller FR/EN |
| Email integrations | Aucun équivalent natif | À faire | Lettre d'information |

### E-commerce
| LearnWorlds | Moodle | Statut | Remarque |
|---|---|---|---|
| Offers, Payments, Plans, Cart | Passerelles de paiement (PayPal, Stripe) | Hors périmètre | Cours gratuit actuel ; à activer au premier cours payant |
| Gifts, Custom deals, Recommendations | Aucun équivalent | Hors périmètre | |
| Licenses | Voir « Multiple seats » | À faire | |
| Coupons | Plugin Enrolment Coupon (cahier des charges) | Hors périmètre | |

### Marketing
| LearnWorlds | Moodle | Statut | Remarque |
|---|---|---|---|
| Marketing forms, Qualification form | Formulaires du site, Feedback | Partiel | Formulaire de contact en place |
| NPS | Enquête Feedback | À faire | Satisfaction en fin de cours |
| Affiliate program | Aucun équivalent | Hors périmètre | |

### Rapports
| LearnWorlds | Moodle | Statut | Remarque |
|---|---|---|---|
| Reports center, Training matrix | Constructeur de rapports, rapport d'achèvement | À faire | Suivi par cohorte et secteur |
| Scheduled reports, Activity logs, Reports log | Rapports planifiés, journaux natifs | À faire | |
| AI insights, Products insights, Sales analytics | Analytics (IntelliBoard dans le cahier des charges) | Hors périmètre | |
| Dashboard impact social | Développement spécifique (20 h au cahier des charges) | À faire | Spécifique datocracy |

### Agents (IA, bêta) et application mobile
| LearnWorlds | Moodle | Statut | Remarque |
|---|---|---|---|
| Agents | Sous-système IA de Moodle | Hors périmètre | À étudier plus tard |
| Mobile app (marque blanche, offre payante) | Application officielle Moodle | Hors périmètre | Marque non personnalisable gratuitement |

## 2. Expérience apprenant : écarts à traiter
| Sujet | État | Piste |
|---|---|---|
| Reprise « là où j'en étais » (bouton Continuer, reprise de lecture) | Absent | Bouton sur le tableau de bord et la fiche du cours |
| Inscription au cours depuis la fiche | Le bouton est en bas de la page d'inscription | Bouton « S'inscrire gratuitement » visible en haut |
| Progression, heures apprises, certificats | Progression OK ; « heures » et « certificats » absents | Certificats PDF + calcul des heures |
| Contenu progressif (drip) | Absent | Restrictions d'accès par date |
| Vidéo (reprise, vitesse) | Lecteur SCORM d'origine | Bunny CDN + VideoJS (10 h au cahier des charges) |
| Interactivité (vidéo interactive, quiz intégrés) | Absent | H5P |
| Gamification (badges, classement) | Badges natifs non configurés | Règles de badges par jalon |
| Parcours et recommandations | Absent | Parcours multi-cours |
| Notifications et rappels | Absent | Messages automatiques (inactivité, fin de module) |
| Contenu adapté au secteur (narration) | Absent | Plugin du cahier des charges (60 h) |

## 3. Proposition de roadmap (à arbitrer)

**Étape 1, avant ouverture au public (environ 50 à 60 h)**
1. Bouton d'inscription visible sur la fiche du cours (3 h).
2. Certificats PDF + « heures apprises » sur le tableau de bord (14 h).
3. Connexion Google et LinkedIn (8 h, applications OAuth à créer côté client).
4. Reprise « Continuer » et contenu progressif (10 h).
5. E-mails : modèles FR/EN habillés, SPF/DKIM/DMARC vérifiés pour que les messages n'aillent pas en spam (6 h).
6. Rapports administrateur : suivi par cohorte, rapport planifié (10 h).

**Étape 2, premier trimestre (environ 100 h)**
1. Champ de profil « secteur » + cohortes + approbation des inscriptions (12 h).
2. Parcours multi-cours et catalogue avec filtres (24 h).
3. H5P et vidéo Bunny/VideoJS (14 h).
4. Badges par jalon, enquête de satisfaction (NPS) (12 h).
5. Dashboard impact social (20 h) puis narration contextualisée (60 h).
6. Message groupé par cohorte et rappels d'inactivité (10 h).

**Étape 3, selon la demande**
- Paiement (PayPal/Stripe) au premier cours payant (8 h).
- Licences par organisation, « multiple seats » (30 h et plus).
- Communauté (forums habillés), lettre d'information, assistant IA, application mobile.

## 4. Décisions à prendre
1. Le **public visé au lancement** : individuel gratuit, ou organisations (ONG, institutions) avec licences ? Cela décide de l'ordre entre étapes 2 et 3.
2. La **migration depuis LearnWorlds** (script de 30 h au cahier des charges) : y a-t-il des apprenants et des cours à reprendre, ou le lancement part de zéro ?
3. Le **modèle économique** : gratuit durable ou payant, pour fixer la date de la passerelle de paiement.
4. Les écrans LearnWorlds réellement utilisés par l'équipe aujourd'hui (rapports, automatisations, communauté), à relever pour ne rien oublier.
