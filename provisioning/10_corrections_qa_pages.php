<?php
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
global $DB;
$w = 'https://staging.datocracy.ai';
function ul($items){ $h='<ul class="dato-footer-list">'; foreach($items as $t=>$u){ $h.='<li><a href="'.$u.'">'.$t.'</a></li>'; } return $h.'</ul>'; }
// reseaux : eviter la transformation automatique des URL en liens (entite pour ':')
set_config('elan_linkedin_url', 'https&#58;//www.linkedin.com/company/datocracy', 'theme_elan');
set_config('elan_twitter_url', 'https&#58;//x.com/', 'theme_elan');
// liens du pied de page
set_config('footer_col_2_body', ul(['Accueil'=>$w.'/','À propos'=>$w.'/mod/page/view.php?id=7','Cours'=>$w.'/course/index.php','Blog'=>$w.'/blog/index.php']), 'theme_elan');
set_config('footer_col_3_body', ul(['FAQ'=>$w.'/mod/page/view.php?id=9','Formateurs'=>$w.'/mod/page/view.php?id=8','Histoires de réussite'=>'#','Témoignages'=>'#','Galerie'=>'#']), 'theme_elan');
// blocs des pages
function getb($cm, $name, $nth=0) { global $DB;
  $ctx = context_module::instance($cm); $r = array_values($DB->get_records('block_instances', ['parentcontextid'=>$ctx->id,'blockname'=>$name], 'defaultweight,id')); return $r[$nth] ?? null; }
function setcfg($bi, $fn) { global $DB; $c = unserialize(base64_decode($bi->configdata)); $fn($c); $DB->set_field('block_instances','configdata',base64_encode(serialize($c)),['id'=>$bi->id]); }
function addpb($cm, $name, array $cfg, $weight) { global $DB; $ctx = context_module::instance($cm); $o = new stdClass(); foreach ($cfg as $k=>$v) $o->$k = $v;
  $DB->insert_record('block_instances', (object)['blockname'=>$name,'parentcontextid'=>$ctx->id,'showinsubcontexts'=>0,'pagetypepattern'=>'mod-page-view','subpagepattern'=>null,'defaultregion'=>'fullwidth-top','defaultweight'=>$weight,'configdata'=>base64_encode(serialize($o)),'timecreated'=>time(),'timemodified'=>time()]); }
// À propos : itemNumber, bloc facilitation + bouton
setcfg(getb(7,'elan_instructor_area'), function($c){ $c->itemNumber = 4; });
$DB->set_field('block_instances','defaultweight',7,['id'=>getb(7,'elan_cta_three')->id]);
$DB->set_field('block_instances','defaultweight',6,['id'=>getb(7,'elan_feedback_2')->id]);
addpb(7,'elan_cta',['top_title'=>'','title'=>"Équipe de facilitation",'content'=>'Ubongabasi Obot, Blaise Booponoyeng Bayuo, Mohamed Merrouch, Ruth Nduta Muriithi, Sandrine Mesa','btn'=>'Voir tous les formateurs','link'=>$w.'/mod/page/view.php?id=8','btn_icon'=>'','img'=>''],5);
// Formateurs : itemNumber par bloc, titre sans doublon
$n=0; foreach ($DB->get_records('block_instances', ['parentcontextid'=>context_module::instance(8)->id,'blockname'=>'elan_instructor_area'], 'defaultweight,id') as $bi) {
  setcfg($bi, function($c) use (&$n){ $c->itemNumber = ($n < 2) ? 4 : 1; if ($n==0) { $c->top_title=''; $c->title=''; } }); $n++; }
// FAQ en 3 groupes
foreach ($DB->get_records('block_instances', ['parentcontextid'=>context_module::instance(9)->id]) as $bi) $DB->delete_records('block_instances',['id'=>$bi->id]);
addpb(9,'elan_faq',['top_title'=>'','title'=>'Pour commencer','faqnumber'=>3,
 'item_title1'=>'Comment accéder aux cours en ligne ?','item_content1'=>'Créez un compte, choisissez un cours et commencez immédiatement depuis votre tableau de bord.',
 'item_title2'=>'Les cours sont-ils gratuits ?','item_content2'=>'Le cours en ligne sur la fracture numérique de genre est gratuit. Les prochains cours seront annoncés sur la plateforme.',
 'item_title3'=>'Puis-je apprendre à mon rythme ?','item_content3'=>'Oui, les cours sont accessibles à tout moment, à votre rythme.'],1);
addpb(9,'elan_faq',['top_title'=>'','title'=>'Cours et certificats','faqnumber'=>3,
 'item_title1'=>'Vais-je recevoir un certificat ?','item_content1'=>"Oui, un certificat de réussite est délivré à l'issue du cours.",
 'item_title2'=>'Puis-je suivre les cours sur mobile ?','item_content2'=>'Oui, la plateforme est accessible depuis tous vos appareils.',
 'item_title3'=>'Ai-je besoin de compétences techniques ?','item_content3'=>"Aucune compétence technique particulière n'est requise pour commencer."],2);
addpb(9,'elan_faq',['top_title'=>'','title'=>'Assistance et sécurité','faqnumber'=>2,
 'item_title1'=>'Comment vous contacter ?','item_content1'=>'Utilisez la page Contact : nous répondons sous 24 h.',
 'item_title2'=>'Mes données personnelles sont-elles protégées ?','item_content2'=>'Vos données sont traitées conformément à notre politique de confidentialité.'],3);
addpb(9,'elan_cta_three',['top_title'=>'','title'=>'Une autre question ?','btn'=>'Nous contacter','link'=>$w.'/user/contactsitesupport.php','btn_icon'=>''],4);
purge_all_caches(); echo "ok\n";
