<?php
require('/tmp/blk_lib.php');
$w = 'https://staging.datocracy.ai';
$b = $w.'/theme/elan/pix/brand/';
// --- cours 2 : renommage + resume + image
$DB->update_record('course', (object)['id'=>2,'fullname'=>"Cours en ligne sur la fracture numérique de genre",'shortname'=>'fracture-numerique-genre','summary'=>"<p>Cours gratuit en ligne : 7 chapitres, 18 h de contenu, vidéos, ressources PDF et questions d'évaluation, avec certification.</p>",'summaryformat'=>1,'timemodified'=>time()]);
$cctx = context_course::instance(2); $fs = get_file_storage();
$fs->delete_area_files($cctx->id, 'course', 'overviewfiles');
$fs->create_file_from_pathname(['contextid'=>$cctx->id,'component'=>'course','filearea'=>'overviewfiles','itemid'=>0,'filepath'=>'/','filename'=>'cover.png'], '/tmp/course1.png');
clearblocks();
set_config('frontpage', '', null); set_config('frontpageloggedin', '', null);
$signup = $w.'/login/signup.php'; $courses = $w.'/course/index.php';
addblock('elan_banner_1', ['top_title'=>"Là où l'upskilling data rencontre la démocratie",'top_icon'=>'ri-book-mark-line','title'=>"Lancez votre montée en compétences en IA et data dès aujourd'hui !",'body'=>"Chez datocracy, la donnée et la démocratie se rencontrent. Apprenez à votre rythme, avec des cours pensés pour l'impact.",'btn'=>"S'inscrire maintenant",'btn_link'=>$signup,'btn_icon'=>'','student_title'=>'Apprenant·es','courses'=>2,'img'=>$b.'photo-woman.png','shape'=>$b.'Brand-shape_orange.png'], 1);
addblock('elan_features_area', ['top_title'=>'Conçu pour vous','title'=>"Prenez place à la table de l'upskilling data",'content'=>"Un apprentissage accessible et flexible, adapté à vos besoins : des cours fondamentaux et sectoriels en climat, santé et MPME.",'features_number'=>3,
 'features_title1'=>'Apprenez à votre façon','features_content1'=>'Des parcours flexibles, à votre rythme, sur tous vos appareils.','img1'=>$b.'photo-keyboard.png','features_btn_icon1'=>'ri-arrow-right-line',
 'features_title2'=>'Ancré dans la communauté','features_content2'=>'Apprenez avec des pairs et des praticiens engagés.','img2'=>$b.'photo-team.png','features_btn_icon2'=>'ri-arrow-right-line',
 'features_title3'=>'Pensé pour la majorité mondiale','features_content3'=>'Des contenus accessibles et contextualisés.','img3'=>$b.'photo-meeting.png','features_btn_icon3'=>'ri-arrow-right-line'], 2);
addblock('elan_about_area', ['top_title'=>'Pourquoi datocracy','title'=>'Des compétences data pour des décisions plus justes','body'=>"Nous aidons celles et ceux qui façonnent l'avenir à comprendre, utiliser et questionner la donnée et l'IA, pour un monde plus équitable.",'features_number'=>4,
 'features_title1'=>'Formateurs experts','features_title2'=>'Accès à vie','features_title3'=>'Apprentissage à distance','features_title4'=>'Certificat de réussite',
 'features_btn_icon1'=>'ri-check-line','features_btn_icon2'=>'ri-check-line','features_btn_icon3'=>'ri-check-line','features_btn_icon4'=>'ri-check-line',
 'btn'=>'Voir tous les cours','btn_link'=>$courses,'btn_icon'=>'','img'=>$b.'photo-hands.png'], 3);
addblock('elan_feedback', ['itemsnumber'=>1,'item_text1'=>"L'accès aux données, c'est l'accès au pouvoir. Pour être des agents du changement, nous devons démocratiser ce pouvoir.",'item_title1'=>'Tarana Burke','item_subtitle1'=>''], 4);
addblock('elan_features_area_two', ['top_title'=>"Résultats d'apprentissage",'title'=>'Ce que vous saurez faire','features_number'=>4,
 'features_title1'=>"Concevoir des IA centrées sur l'humain",'features_content1'=>'Créez des systèmes qui servent les personnes.',
 'features_title2'=>'Façonner les décisions avec des récits de données locaux','features_content2'=>'Racontez des histoires de données qui comptent.',
 'features_title3'=>'Détecter les biais et nettoyer les algorithmes','features_content3'=>'Auditez et corrigez vos modèles.',
 'features_title4'=>'Renforcer prévisions et analyse des risques','features_content4'=>"Anticipez avec des prévisions pilotées par l'IA.",
 'features_icon1'=>'ri-user-heart-line','features_icon2'=>'ri-bar-chart-2-line','features_icon3'=>'ri-shield-check-line','features_icon4'=>'ri-line-chart-line'], 5);
addblock('elan_course_filter', ['top_title'=>'Nos cours','title'=>'Nos cours de qualité','content'=>'','courses'=>[2],'btn'=>'Voir tous les cours','link'=>$courses,'btn_icon'=>'','total_student_title'=>'Apprenant·es'], 6);
addblock('elan_count_area', ['count_number'=>4,'count1'=>120,'suffix1'=>'+','count_title1'=>'Apprenants','count2'=>29,'suffix2'=>'+','count_title2'=>'Vidéos','count3'=>100,'suffix3'=>'+','count_title3'=>'heures de contenu','count4'=>47,'suffix4'=>'','count_title4'=>'Devoirs'], 7);
addblock('elan_feedback_2', ['top_title'=>'Témoignages','title'=>'Ce que disent nos apprenants','itemsnumber'=>3,
 'item_text1'=>"Le cours a rendu les données accessibles. J'ai enfin su comment utiliser les chiffres pour expliquer notre impact aux bailleurs.",'item_title1'=>'Amara Okafor','item_subtitle1'=>'Chargée de programme, Lagos',
 'item_text2'=>"Souple et pratique. J'apprenais après le travail et j'appliquais le lendemain dans ma petite entreprise.",'item_title2'=>'Fatou Ndiaye','item_subtitle2'=>'Gérante de MPME, Dakar',
 'item_text3'=>"Une communauté qui vous prend là où vous en êtes. Je suis passée de timide face aux données à responsable du reporting de notre ONG.",'item_title3'=>'Grace Mwangi','item_subtitle3'=>'Responsable données, Nairobi'], 8);
addblock('elan_video_area', ['top_title'=>"Histoires d'apprenants",'title'=>'Regardez les témoignages de nos apprenants','img'=>$b.'photo-meeting.png','video'=>''], 9);
addblock('elan_cta', ['top_title'=>'','title'=>"Lancez votre montée en compétences aujourd'hui",'content'=>'Rejoignez une communauté qui démocratise la donnée. Inscription gratuite.','btn'=>"S'inscrire gratuitement",'link'=>$signup,'btn_icon'=>'','img'=>$b.'photo-woman.png'], 10);
addblock('elan_steps', ['top_title'=>'Nous vous comprenons','title'=>'Vos besoins en data et IA, relevons-les ensemble','steps_number'=>3,
 'steps_title1'=>'Budgets serrés, agendas chargés','steps_content1'=>'Des formats courts, abordables et accessibles à tout moment.',
 'steps_title2'=>'Bloqué ? Avancez à votre rythme','steps_content2'=>'Progressez sans pression, avec un soutien à chaque étape.',
 'steps_title3'=>"Vous n'êtes pas seul·e",'steps_content3'=>"Une communauté d'apprenants et de mentors à vos côtés.",
 'steps_top_title1'=>'01','steps_top_title2'=>'02','steps_top_title3'=>'03'], 11);
addblock('elan_blog_area', ['top_title'=>'Notre blog','title'=>'Nos dernières publications','button_text'=>'Tous les articles','btn_icon'=>''], 12);
addblock('elan_cta_three', ['top_title'=>'','title'=>'Obtenez votre certificat grâce à un examen en ligne','btn'=>'Voir les certifications','link'=>$courses,'btn_icon'=>''], 13);
addblock('elan_faq', ['top_title'=>'FAQ','title'=>'Questions fréquentes','faqnumber'=>3,
 'item_title1'=>'Comment accéder aux cours en ligne ?','item_content1'=>'Créez un compte, choisissez un cours et commencez immédiatement depuis votre tableau de bord.',
 'item_title2'=>'Vais-je recevoir un certificat ?','item_content2'=>"Oui, un certificat de réussite est délivré à l'issue du cours.",
 'item_title3'=>'Puis-je suivre les cours sur mobile ?','item_content3'=>'Oui, la plateforme est accessible depuis tous vos appareils.'], 14);
addblock('elan_contact_area', ['top_title'=>'Contact','title'=>'Contactez-nous et progressez vite, simplement','content'=>'Une question sur nos cours ou un projet de formation ? Écrivez-nous, nous répondons sous 24 h.','features_number'=>2,
 'features_title1'=>'LinkedIn','features_content1'=>'linkedin.com/company/datocracy','features_icon1'=>'ri-linkedin-fill',
 'features_title2'=>'Site web','features_content2'=>'www.datocracy.ai','features_icon2'=>'ri-global-line'], 15);
purge_all_caches();
