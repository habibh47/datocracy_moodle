<?php
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/course/modlib.php');
global $DB, $USER;
$USER = get_admin(); \core\session\manager::set_user($USER);
$w = 'https://staging.datocracy.ai'; $b = $w.'/theme/elan/pix/brand/';
$courses = $w.'/course/index.php';

function mkpage($idnumber, $name, $html='<p></p>') {
  global $DB;
  if ($cm = $DB->get_record('course_modules', ['idnumber'=>$idnumber, 'course'=>1])) { course_delete_module($cm->id); }
  $mi = new stdClass();
  $mi->modulename='page'; $mi->module=$DB->get_field('modules','id',['name'=>'page']);
  $mi->course=1; $mi->section=0; $mi->visible=1; $mi->visibleoncoursepage=1; $mi->name=$name; $mi->cmidnumber=$idnumber;
  $mi->introeditor=['text'=>'','format'=>FORMAT_HTML,'itemid'=>0]; $mi->page=['text'=>$html,'format'=>FORMAT_HTML,'itemid'=>0];
  $mi->display=5; $mi->printintro=0; $mi->printheading=0; $mi->printlastmodified=0; $mi->showdescription=0; $mi->groupmode=0; $mi->groupingid=0; $mi->completion=0;
  $mi = add_moduleinfo($mi, get_course(1)); echo "page $name cmid={$mi->coursemodule}\n"; return $mi->coursemodule;
}
function addpb($cm, $name, array $cfg, $weight) {
  global $DB; $ctx = context_module::instance($cm); $o = new stdClass(); foreach ($cfg as $k=>$v) $o->$k = $v;
  return $DB->insert_record('block_instances', (object)['blockname'=>$name,'parentcontextid'=>$ctx->id,'showinsubcontexts'=>0,'pagetypepattern'=>'mod-page-view','subpagepattern'=>null,'defaultregion'=>'fullwidth-top','defaultweight'=>$weight,'configdata'=>base64_encode(serialize($o)),'timecreated'=>time(),'timemodified'=>time()]);
}
$cta = ['top_title'=>'','title'=>'Obtenez votre certificat grâce à un examen en ligne','btn'=>'Voir les cours','link'=>$courses,'btn_icon'=>''];
$t3a = ['top_title'=>'','title'=>'','itemsnumber'=>3,
 'item_text1'=>"Le cours a rendu les données accessibles. J'ai enfin su comment utiliser les chiffres pour expliquer notre impact aux bailleurs.",'item_title1'=>'Amara Okafor','item_subtitle1'=>'Chargée de programme, Lagos',
 'item_text2'=>"Souple et pratique. J'apprenais après le travail et j'appliquais le lendemain dans ma petite entreprise.",'item_title2'=>'Fatou Ndiaye','item_subtitle2'=>'Gérante de MPME, Dakar',
 'item_text3'=>"Une communauté qui vous prend là où vous en êtes. Je suis passée de timide face aux données à responsable du reporting de notre ONG.",'item_title3'=>'Grace Mwangi','item_subtitle3'=>'Responsable données, Nairobi'];
$t3b = ['top_title'=>'','title'=>'','itemsnumber'=>3,
 'item_text1'=>"Je crée maintenant des tableaux de bord simples pour nos conseils d'administration. Le certificat m'a donné confiance.",'item_title1'=>'Kofi Mensah','item_subtitle1'=>'Chargé de suivi-évaluation, Accra',
 'item_text2'=>"Des leçons courtes, des exemples clairs. Pensé pour les professionnelles pressées comme moi.",'item_title2'=>'Aisha Bello','item_subtitle2'=>'Cheffe de projet, Abuja',
 'item_text3'=>"Le contenu bilingue a permis à toute mon équipe d'apprendre ensemble.",'item_title3'=>'Mariam Traoré','item_subtitle3'=>"Coordinatrice d'ONG, Abidjan"];

// Histoires de réussite
$cm = mkpage('page-histoires', 'Notre histoire de réussite'); $ids['histoires']=$cm;
addpb($cm,'elan_steps',['top_title'=>'En 4 étapes','title'=>'4 étapes simples vers la réussite','steps_number'=>4,
 'steps_title1'=>'Évaluez votre niveau','steps_content1'=>'Faites le point sur vos compétences en données et en IA.',
 'steps_title2'=>'Apprenez à votre rythme','steps_content2'=>'Vidéos, PDF et quiz accessibles à tout moment.',
 'steps_title3'=>'Pratiquez sur des cas réels','steps_content3'=>'Appliquez immédiatement à votre travail et à votre communauté.',
 'steps_title4'=>'Obtenez votre certificat','steps_content4'=>'Validez vos acquis et partagez votre réussite.',
 'steps_top_title1'=>'1','steps_top_title2'=>'2','steps_top_title3'=>'3','steps_top_title4'=>'4'],1);
addpb($cm,'elan_feedback_2',['top_title'=>'Témoignages','title'=>'Ce que disent nos apprenants']+$t3a,2);
addpb($cm,'elan_cta_three',$cta+['title'=>'Obtenez votre certificat grâce à un examen en ligne'],3);
// Témoignages
$cm = mkpage('page-temoignages', 'Témoignages'); $ids['temoignages']=$cm;
addpb($cm,'elan_feedback_2',$t3a,1); addpb($cm,'elan_feedback_2',$t3b,2); addpb($cm,'elan_cta_three',$cta,3);
// Galerie
$cm = mkpage('page-galerie', 'Galerie'); $ids['galerie']=$cm;
$gid = addpb($cm,'elan_gallery',['class'=>''],1);
$fs = get_file_storage(); $bctx = context_block::instance($gid);
foreach (['photo-keyboard','photo-team','photo-woman','photo-code','photo-binary','photo-hands','photo-robot','photo-meeting'] as $f) {
  $fs->create_file_from_pathname(['contextid'=>$bctx->id,'component'=>'block_elan_gallery','filearea'=>'content','itemid'=>0,'filepath'=>'/','filename'=>$f.'.png'], '/tmp/'.$f.'.png');
}
addpb($cm,'elan_cta_three',$cta,2);
// Pages légales (contenu du Figma)
function legal($sommaire, $sections) {
  $h = '<div class="dato-legal"><h3>Sommaire</h3><ul>'; foreach ($sommaire as $s) $h .= '<li>'.$s.'</li>'; $h .= '</ul><p><em>Dernière mise à jour : 1er janvier 2026</em></p>';
  foreach ($sections as $t=>$p) $h .= '<h3>'.$t.'</h3><p>'.$p.'</p>'; return $h.'</div>'; }
$ids['cgv'] = mkpage('page-cgv','Conditions générales', legal(['1. Acceptation des conditions','2. Comptes utilisateurs','3. Contenus et propriété intellectuelle','4. Paiements et remboursements','5. Contact'],[
 '1. Acceptation des conditions'=>"En accédant à la plateforme datocracy, vous acceptez les présentes conditions générales d'utilisation. Si vous n'êtes pas d'accord, veuillez ne pas utiliser le service.",
 '2. Comptes utilisateurs'=>'Vous êtes responsable de la confidentialité de vos identifiants et de toute activité réalisée depuis votre compte.',
 '3. Contenus et propriété intellectuelle'=>'Les cours, vidéos et ressources sont protégés. Toute reproduction ou diffusion sans autorisation écrite est interdite.',
 '4. Paiements et remboursements'=>"Les prix sont indiqués sur chaque cours. Les demandes de remboursement sont étudiées sous 14 jours après l'achat.",
 '5. Contact'=>'Pour toute question sur ces conditions, utilisez le formulaire de la page Contact.']));
$ids['privacy'] = mkpage('page-confidentialite','Politique de confidentialité', legal(['1. Données collectées','2. Utilisation des données','3. Partage et conservation','4. Vos droits','5. Contact'],[
 '1. Données collectées'=>"Nous collectons les informations que vous fournissez à l'inscription (nom, e-mail, profession) ainsi que des données d'usage de la plateforme.",
 '2. Utilisation des données'=>'Vos données servent à fournir les cours, suivre votre progression, délivrer les certificats et améliorer le service.',
 '3. Partage et conservation'=>'Nous ne vendons jamais vos données. Elles sont conservées le temps nécessaire aux finalités décrites.',
 '4. Vos droits'=>'Vous pouvez accéder à vos données, les corriger ou demander leur suppression à tout moment.',
 '5. Contact'=>'Pour toute demande, utilisez le formulaire de la page Contact.']));
$ids['cookies'] = mkpage('page-cookies','Politique de cookies', legal(["1. Qu'est-ce qu'un cookie ?",'2. Cookies essentiels','3. Cookies de mesure','4. Gérer vos préférences'],[
 "1. Qu'est-ce qu'un cookie ?"=>"Un cookie est un petit fichier déposé sur votre appareil pour mémoriser vos préférences et mesurer l'audience.",
 '2. Cookies essentiels'=>'Ils sont nécessaires au fonctionnement de la plateforme (connexion, sécurité, langue).',
 '3. Cookies de mesure'=>"Ils nous aident à comprendre comment le site est utilisé afin de l'améliorer.",
 '4. Gérer vos préférences'=>'Vous pouvez accepter, refuser ou personnaliser vos choix à tout moment depuis la bannière cookies.']));
// pied de page
function ul($items){ $h='<ul class="dato-footer-list">'; foreach($items as $t=>$u){ $h.='<li><a href="'.$u.'">'.$t.'</a></li>'; } return $h.'</ul>'; }
$p = $w.'/mod/page/view.php?id=';
set_config('footer_col_3_body', ul(['FAQ'=>$p.'9','Formateurs'=>$p.'8','Histoires de réussite'=>$p.$ids['histoires'],'Témoignages'=>$p.$ids['temoignages'],'Galerie'=>$p.$ids['galerie']]), 'theme_elan');
set_config('footer_col_4_body', ul(['Conditions générales'=>$p.$ids['cgv'],'Politique de confidentialité'=>$p.$ids['privacy'],'Politique de cookies'=>$p.$ids['cookies']]), 'theme_elan');
// d minuscule : nom du site, copyright
$DB->set_field('course','fullname','datocracy',['id'=>1]); $DB->set_field('course','shortname','datocracy',['id'=>1]);
set_config('footer_copyright', '© 2026 datocracy. Tous droits réservés.', 'theme_elan');
purge_all_caches(); echo json_encode($ids)."\n";
