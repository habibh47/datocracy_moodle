<?php
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/course/modlib.php');
global $DB, $USER;
$USER = get_admin(); \core\session\manager::set_user($USER);
$w = 'https://staging.datocracy.ai'; $b = $w.'/theme/elan/pix/brand/';
$signup = $w.'/login/signup.php'; $courses = $w.'/course/index.php';

function mkpage($idnumber, $name) {
  global $DB;
  if ($cm = $DB->get_record('course_modules', ['idnumber'=>$idnumber, 'course'=>1])) { course_delete_module($cm->id); }
  $mi = new stdClass();
  $mi->modulename = 'page'; $mi->module = $DB->get_field('modules','id',['name'=>'page']);
  $mi->course = 1; $mi->section = 0; $mi->visible = 1; $mi->visibleoncoursepage = 1;
  $mi->name = $name; $mi->cmidnumber = $idnumber;
  $mi->introeditor = ['text'=>'', 'format'=>FORMAT_HTML, 'itemid'=>0];
  $mi->page = ['text'=>'<p></p>', 'format'=>FORMAT_HTML, 'itemid'=>0];
  $mi->display = 5; $mi->printintro = 0; $mi->printheading = 0; $mi->printlastmodified = 0;
  $mi->showdescription = 0; $mi->groupmode = 0; $mi->groupingid = 0; $mi->completion = 0;
  $mi = add_moduleinfo($mi, get_course(1));
  echo "page $name cmid={$mi->coursemodule}\n";
  return $mi->coursemodule;
}
function addpb($cmid, $name, array $cfg, $weight) {
  global $DB;
  $ctx = context_module::instance($cmid);
  $o = new stdClass(); foreach ($cfg as $k=>$v) $o->$k = $v;
  $DB->insert_record('block_instances', (object)['blockname'=>$name, 'parentcontextid'=>$ctx->id, 'showinsubcontexts'=>0,
    'pagetypepattern'=>'mod-page-view', 'subpagepattern'=>null, 'defaultregion'=>'fullwidth-top', 'defaultweight'=>$weight,
    'configdata'=>base64_encode(serialize($o)), 'timecreated'=>time(), 'timemodified'=>time()]);
}
$stats = ['count_number'=>4,'count1'=>120,'suffix1'=>'+','count_title1'=>'Apprenants','count2'=>29,'suffix2'=>'+','count_title2'=>'Vidéos','count3'=>100,'suffix3'=>'+','count_title3'=>'heures de contenu','count4'=>47,'suffix4'=>'','count_title4'=>'Devoirs'];
$testi = ['top_title'=>'Témoignages','title'=>'Ce que disent nos apprenants','itemsnumber'=>3,
 'item_text1'=>"Le cours a rendu les données accessibles. J'ai enfin su comment utiliser les chiffres pour expliquer notre impact aux bailleurs.",'item_title1'=>'Amara Okafor','item_subtitle1'=>'Chargée de programme, Lagos',
 'item_text2'=>"Souple et pratique. J'apprenais après le travail et j'appliquais le lendemain dans ma petite entreprise.",'item_title2'=>'Fatou Ndiaye','item_subtitle2'=>'Gérante de MPME, Dakar',
 'item_text3'=>"Une communauté qui vous prend là où vous en êtes. Je suis passée de timide face aux données à responsable du reporting de notre ONG.",'item_title3'=>'Grace Mwangi','item_subtitle3'=>'Responsable données, Nairobi'];
$cta = ['top_title'=>'','title'=>'Transformer la peur des données en aisance avec les données','btn'=>"S'inscrire maintenant",'link'=>$signup,'btn_icon'=>''];

// ---- À propos
$cm = mkpage('page-a-propos', 'À propos de datocracy');
addpb($cm,'elan_about_area',['top_title'=>'Notre histoire','title'=>'Là où la donnée rencontre la démocratie','body'=>"datocracy fait se rencontrer données et démocratie pour créer un monde plus équitable. Nous croyons que la donnée doit donner du pouvoir à tous, pas seulement à quelques-uns, et nous rendons l'upskilling data accessible à la majorité mondiale.",'features_number'=>0,'btn'=>'Voir les cours','btn_link'=>$courses,'btn_icon'=>'','img'=>$b.'photo-hands.png'],1);
addpb($cm,'elan_features_area_two',['top_title'=>'Nos engagements','title'=>'Mission, vision et valeurs','features_number'=>3,
 'features_title1'=>'Mission','features_content1'=>'Fusionner données et démocratie pour créer un monde plus équitable.',
 'features_title2'=>'Vision','features_content2'=>"Rendre l'upskilling data accessible à la majorité mondiale.",
 'features_title3'=>'Valeurs','features_content3'=>'Inclusivité, apprentissage continu, innovation globale et fiabilité.',
 'features_icon1'=>'ri-focus-3-line','features_icon2'=>'ri-eye-line','features_icon3'=>'ri-heart-line'],2);
addpb($cm,'elan_count_area',$stats,3);
addpb($cm,'elan_instructor_area',['top_title'=>"L'équipe",'title'=>'Équipe dirigeante','item'=>4,'items'=>4,
 'instructor_name1'=>'Dr. Ronda Železný-Green','instructor_designation1'=>'Direction générale (CEO)','instructor_img1'=>$b.'avatar-RZ.png',
 'instructor_name2'=>'Jessica Brown','instructor_designation2'=>'Conseil en communication','instructor_img2'=>$b.'avatar-JB.png',
 'instructor_name3'=>'Yaovi Habib Lazare Houndekindo','instructor_designation3'=>'Direction des opérations francophones','instructor_img3'=>$b.'avatar-YH.png',
 'instructor_name4'=>'Maathangi Mohan','instructor_designation4'=>'Responsable programmes data et partenariats','instructor_img4'=>$b.'avatar-MM.png'],4);
addpb($cm,'elan_feedback_2',$testi,5);
addpb($cm,'elan_cta_three',$cta,6);

// ---- Formateurs
$cm2 = mkpage('page-formateurs', 'Nos formateurs');
$desc = [
 ['Dr. Ronda Železný-Green','Direction générale (CEO)','RZ'],['Jessica Brown','Conseil en communication','JB'],
 ['Yaovi Habib Lazare Houndekindo','Direction des opérations francophones','YH'],['Maathangi Mohan','Responsable programmes data et partenariats','MM'],
 ['Ubongabasi Obot','Facilitation','UO'],['Blaise Booponoyeng Bayuo','Facilitation','BB'],['Mohamed Merrouch','Facilitation','ME'],['Ruth Nduta Muriithi','Facilitation','RN'],['Sandrine Mesa','Facilitation','SM']];
$chunks = array_chunk($desc, 4); $wgt = 1;
foreach ($chunks as $ci=>$ch) {
  $cfg = ['top_title'=>$ci==0?'Équipe':'','title'=>$ci==0?'Nos formateurs':'','item'=>count($ch),'items'=>count($ch)];
  foreach ($ch as $i=>$p) { $n=$i+1; $cfg["instructor_name$n"]=$p[0]; $cfg["instructor_designation$n"]=$p[1]; $cfg["instructor_img$n"]=$b.'avatar-'.$p[2].'.png'; }
  addpb($cm2,'elan_instructor_area',$cfg,$wgt++);
}

// ---- FAQ
$cm3 = mkpage('page-faq', 'Questions fréquentes');
addpb($cm3,'elan_faq',['top_title'=>'Pour commencer','title'=>'Questions fréquentes','faqnumber'=>6,
 'item_title1'=>'Comment accéder aux cours en ligne ?','item_content1'=>'Créez un compte, choisissez un cours et commencez immédiatement depuis votre tableau de bord.',
 'item_title2'=>'Les cours sont-ils gratuits ?','item_content2'=>"Le cours en ligne sur la fracture numérique de genre est gratuit. Les prochains cours seront annoncés sur la plateforme.",
 'item_title3'=>'Puis-je apprendre à mon rythme ?','item_content3'=>'Oui, les cours sont accessibles à tout moment, à votre rythme.',
 'item_title4'=>'Vais-je recevoir un certificat ?','item_content4'=>"Oui, un certificat de réussite est délivré à l'issue du cours.",
 'item_title5'=>'Puis-je suivre les cours sur mobile ?','item_content5'=>'Oui, la plateforme est accessible depuis tous vos appareils.',
 'item_title6'=>'Ai-je besoin de compétences techniques ?','item_content6'=>'Aucune compétence technique particulière n’est requise pour commencer.'],1);
addpb($cm3,'elan_faq',['top_title'=>'Assistance et sécurité','title'=>'','faqnumber'=>2,
 'item_title1'=>'Comment vous contacter ?','item_content1'=>'Utilisez la page Contact : nous répondons sous 24 h.',
 'item_title2'=>'Mes données personnelles sont-elles protégées ?','item_content2'=>'Vos données sont traitées conformément à notre politique de confidentialité.'],2);
addpb($cm3,'elan_cta_three',['top_title'=>'','title'=>'Une autre question ?','btn'=>'Nous contacter','link'=>$w.'/user/contactsitesupport.php','btn_icon'=>''],3);

// ---- Contact
$cm4 = mkpage('page-contact', 'Contactez-nous');
addpb($cm4,'elan_contact_area',['top_title'=>'Contact','title'=>'Contactez-nous','content'=>'Écrivez-nous, nous répondons sous 24 h.','features_number'=>3,
 'features_title1'=>'LinkedIn','features_content1'=>'linkedin.com/company/datocracy','features_icon1'=>'ri-linkedin-fill',
 'features_title2'=>'Site web','features_content2'=>'www.datocracy.ai','features_icon2'=>'ri-global-line',
 'features_title3'=>'Réponse rapide','features_content3'=>'Réponse sous 24 h','features_icon3'=>'ri-time-line'],1);
addpb($cm4,'elan_cta_three',['top_title'=>'','title'=>'Envoyez-nous un message','btn'=>'Envoyer le message','link'=>$w.'/user/contactsitesupport.php','btn_icon'=>''],2);

// ---- menu
set_config('custommenuitems', "Accueil|/\nÀ propos|/mod/page/view.php?id=$cm\nCours|/course/index.php\nContact|/mod/page/view.php?id=$cm4\nBlog|/blog/index.php", null);
purge_all_caches();
echo "ids about=$cm formateurs=$cm2 faq=$cm3 contact=$cm4\n";
