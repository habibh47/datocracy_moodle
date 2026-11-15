<?php
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
global $DB;
$w = 'https://staging.datocracy.ai'; $b = $w.'/theme/elan/pix/brand/';
function legal($sommaire, $sections) {
  $h = '<div class="dato-legal"><h3>Sommaire</h3><ul>'; foreach ($sommaire as $s) $h .= '<li>'.$s.'</li>'; $h .= '</ul><p><em>Dernière mise à jour : 1er janvier 2026</em></p>';
  foreach ($sections as $t=>$p) $h .= '<h3>'.$t.'</h3><p>'.$p.'</p>'; return $h.'</div>'; }
$contents = [
 'page-cgv' => legal(['1. Acceptation des conditions','2. Comptes utilisateurs','3. Contenus et propriété intellectuelle','4. Paiements et remboursements','5. Contact'],[
  '1. Acceptation des conditions'=>"En accédant à la plateforme datocracy, vous acceptez les présentes conditions générales d'utilisation. Si vous n'êtes pas d'accord, veuillez ne pas utiliser le service.",
  '2. Comptes utilisateurs'=>'Vous êtes responsable de la confidentialité de vos identifiants et de toute activité réalisée depuis votre compte.',
  '3. Contenus et propriété intellectuelle'=>'Les cours, vidéos et ressources sont protégés. Toute reproduction ou diffusion sans autorisation écrite est interdite.',
  '4. Paiements et remboursements'=>"Les prix sont indiqués sur chaque cours. Les demandes de remboursement sont étudiées sous 14 jours après l'achat.",
  '5. Contact'=>'Pour toute question sur ces conditions, utilisez le formulaire de la page Contact.']),
 'page-confidentialite' => legal(['1. Données collectées','2. Utilisation des données','3. Partage et conservation','4. Vos droits','5. Contact'],[
  '1. Données collectées'=>"Nous collectons les informations que vous fournissez à l'inscription (nom, e-mail, profession) ainsi que des données d'usage de la plateforme.",
  '2. Utilisation des données'=>'Vos données servent à fournir les cours, suivre votre progression, délivrer les certificats et améliorer le service.',
  '3. Partage et conservation'=>'Nous ne vendons jamais vos données. Elles sont conservées le temps nécessaire aux finalités décrites.',
  '4. Vos droits'=>'Vous pouvez accéder à vos données, les corriger ou demander leur suppression à tout moment.',
  '5. Contact'=>'Pour toute demande, utilisez le formulaire de la page Contact.']),
 'page-cookies' => legal(["1. Qu'est-ce qu'un cookie ?",'2. Cookies essentiels','3. Cookies de mesure','4. Gérer vos préférences'],[
  "1. Qu'est-ce qu'un cookie ?"=>"Un cookie est un petit fichier déposé sur votre appareil pour mémoriser vos préférences et mesurer l'audience.",
  '2. Cookies essentiels'=>'Ils sont nécessaires au fonctionnement de la plateforme (connexion, sécurité, langue).',
  '3. Cookies de mesure'=>"Ils nous aident à comprendre comment le site est utilisé afin de l'améliorer.",
  '4. Gérer vos préférences'=>'Vous pouvez accepter, refuser ou personnaliser vos choix à tout moment depuis la bannière cookies.']),
];
foreach ($contents as $idn=>$html) {
  $cm = $DB->get_record('course_modules', ['idnumber'=>$idn,'course'=>1]);
  $DB->update_record('page', (object)['id'=>$cm->instance,'content'=>$html,'contentformat'=>FORMAT_HTML,'timemodified'=>time()]);
  echo "$idn content set (instance {$cm->instance})\n";
}
// cartes numérotées à la place de la frise (Histoires de réussite) et accueil "Nous vous comprenons"
function swap($blockname, $newname, $mutate) { global $DB;
  foreach ($DB->get_records('block_instances', ['blockname'=>$blockname]) as $bi) {
    $c = unserialize(base64_decode($bi->configdata)); $c = $mutate($c);
    $DB->update_record('block_instances', (object)['id'=>$bi->id,'blockname'=>$newname,'configdata'=>base64_encode(serialize($c))]); echo "swapped {$bi->id}\n"; } }
swap('elan_steps','elan_features_area_two', function($c){
  $n = (int)$c->steps_number; $o = new stdClass(); $o->top_title=$c->top_title; $o->title=$c->title; $o->features_number=$n;
  $icons = $n==4 ? ['ri-number-1','ri-number-2','ri-number-3','ri-number-4'] : ['ri-wallet-3-line','ri-speed-up-line','ri-team-line'];
  for ($i=1;$i<=$n;$i++){ $o->{"features_title$i"}=$c->{"steps_title$i"}; $o->{"features_content$i"}=$c->{"steps_content$i"}; $o->{"features_icon$i"}=$icons[$i-1]; }
  return $o; });
// visuel (mains empilées) sur Histoires de réussite
$ctx = context_module::instance($DB->get_field('course_modules','id',['idnumber'=>'page-histoires','course'=>1]));
$DB->insert_record('block_instances', (object)['blockname'=>'elan_video_area','parentcontextid'=>$ctx->id,'showinsubcontexts'=>0,'pagetypepattern'=>'mod-page-view','subpagepattern'=>null,'defaultregion'=>'fullwidth-top','defaultweight'=>0,'configdata'=>base64_encode(serialize((object)['top_title'=>'','title'=>'','img'=>$b.'photo-meeting.png','video'=>''])),'timecreated'=>time(),'timemodified'=>time()]);
// sous-titre du bloc certification
foreach ($DB->get_records('block_instances', ['blockname'=>'elan_cta_three']) as $bi) {
  $c = unserialize(base64_decode($bi->configdata));
  if (strpos($c->title,'Obtenez votre certificat')===0 && strpos($c->title,'dato-sub')===false) {
    $c->title = 'Obtenez votre certificat grâce à un examen en ligne<br><span class="dato-sub">Validez vos compétences en données et en IA avec une certification reconnue, à votre rythme.</span>';
    $DB->set_field('block_instances','configdata',base64_encode(serialize($c)),['id'=>$bi->id]); }
}
purge_all_caches(); echo "ok\n";
