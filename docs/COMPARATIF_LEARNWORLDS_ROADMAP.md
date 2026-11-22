# Comparatif LearnWorlds / Moodle datocracy et proposition de roadmap (20/11/2026)

## Sources et limites
- **LearnWorlds** : arborescence complète du menu du tableau de bord auteur (offre « Learning Center ») et **45 captures d'écran** fournies par l'équipe (20/11/2026), analysées écran par écran dans un fichier conservé en local (données du client). Les captures ne couvrent pas l'inscription ni la connexion, ni le lecteur mobile.
- **Cahier des charges de refonte** (225 h, 6 sprints), rédigé avant le choix du thème : plugins, développements et risques prévus.
- **Moodle datocracy** : état de staging, voir `docs/ETAT.md` (Moodle 5.2.2, thème Elan).
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
| Offers, Payments, Plans, Cart | Inscription par paiement + passerelle adaptée à l'Afrique : Mobile Money (Orange Money, Wave, MTN MoMo) et carte via un agrégateur (PayDunya, CinetPay, Flutterwave ou Paystack) | À faire (au premier cours payant) | Cours gratuit actuel. Plugin Moodle de l'agrégateur retenu **à vérifier** avant chiffrage ; sinon développement d'une passerelle de paiement |
| Gifts, Custom deals, Recommendations | Aucun équivalent | Hors périmètre | |
| Multiple seats, Licenses | | Hors périmètre | Écartés par décision du client |
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
- Paiement par Mobile Money et carte (agrégateur à choisir) au premier cours payant : 8 h à **re-estimer** selon le plugin disponible.
- Communauté (forums habillés), lettre d'information, assistant IA, application mobile.

## 4. Décisions à prendre
1. La **migration depuis LearnWorlds** (script de 30 h au cahier des charges) : le compte LearnWorlds actuel contient déjà des utilisateurs, des certificats et des campagnes d'e-mails. Faut-il reprendre les comptes, les progressions et les certificats, ou le lancement part-il de zéro ?
2. Le **modèle économique** : gratuit durable ou payant, pour fixer la date de la passerelle de paiement.
3. L'**agrégateur de paiement** : PayDunya, CinetPay, Flutterwave ou Paystack (pays couverts, commissions, plugin Moodle existant).
4. Les **automatisations** à reproduire en priorité (bienvenue, relance d'inactivité, félicitations) : une seule règle, l'e-mail de bienvenue, est active aujourd'hui.

Décisions déjà prises : pas de « multiple seats » ni de licences par organisation ; paiement par les moyens les plus utilisés en Afrique.
