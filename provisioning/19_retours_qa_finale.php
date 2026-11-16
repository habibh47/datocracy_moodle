<?php
// Retours de la dernière relecture : collage du hero, espace insécable avant « ! », bloc en double sur la page Contact,
// formateurs en grille de 3, pages légales avec sommaire en carte.
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
global $DB;
$b = 'https://staging.datocracy.ai/theme/elan/pix/brand/';

// Hero : collage de 4 photos, « ! » insécable
foreach ($DB->get_records('block_instances', ['blockname' => 'elan_banner_1']) as $bi) {
    $c = unserialize(base64_decode($bi->configdata));
    $c->img = $b . 'hero-collage.png';
    $c->title = str_replace(" !", "&nbsp;!", $c->title);
    $DB->set_field('block_instances', 'configdata', base64_encode(serialize($c)), ['id' => $bi->id]);
    echo "hero mis à jour\n";
}
function cmid($idn) { global $DB; return $DB->get_field('course_modules', 'id', ['idnumber' => $idn, 'course' => 1]); }

// Page Contact : retirer le bandeau « Envoyez-nous un message » (doublon de la carte)
$ctx = context_module::instance(cmid('page-contact'));
foreach ($DB->get_records('block_instances', ['parentcontextid' => $ctx->id, 'blockname' => 'elan_cta_three']) as $bi) {
    $DB->delete_records('block_instances', ['id' => $bi->id]);
    echo "bandeau en double retiré\n";
}

// Formateurs : 3 par rangée
$ctx = context_module::instance(cmid('page-formateurs'));
foreach ($DB->get_records('block_instances', ['parentcontextid' => $ctx->id]) as $bi) {
    $DB->delete_records('block_instances', ['id' => $bi->id]);
}
$people = [
 ['Dr. Ronda Železný-Green', 'Direction générale (CEO)', 'RZ'], ['Jessica Brown', 'Conseil en communication', 'JB'],
 ['Yaovi Habib Lazare Houndekindo', 'Direction des opérations francophones', 'YH'], ['Maathangi Mohan', 'Responsable programmes data et partenariats', 'MM'],
 ['Ubongabasi Obot', 'Facilitation', 'UO'], ['Blaise Booponoyeng Bayuo', 'Facilitation', 'BB'], ['Mohamed Merrouch', 'Facilitation', 'ME'],
 ['Ruth Nduta Muriithi', 'Facilitation', 'RN'], ['Sandrine Mesa', 'Facilitation', 'SM']];
foreach (array_chunk($people, 3) as $ci => $ch) {
    $cfg = new stdClass();
    $cfg->top_title = $ci == 0 ? 'Équipe' : '';
    $cfg->title = '';
    $cfg->item = 3; $cfg->items = 3; $cfg->itemNumber = 3;
    foreach ($ch as $i => $p) { $n = $i + 1; $cfg->{"instructor_name$n"} = $p[0]; $cfg->{"instructor_designation$n"} = $p[1]; $cfg->{"instructor_img$n"} = $b . 'avatar-' . $p[2] . '.png'; }
    $DB->insert_record('block_instances', (object) ['blockname' => 'elan_instructor_area', 'parentcontextid' => $ctx->id, 'showinsubcontexts' => 0,
        'pagetypepattern' => 'mod-page-view', 'subpagepattern' => null, 'defaultregion' => 'fullwidth-top', 'defaultweight' => $ci + 1,
        'configdata' => base64_encode(serialize($cfg)), 'timecreated' => time(), 'timemodified' => time()]);
}
echo "formateurs : 3 rangées de 3\n";

// Pages légales : sommaire en carte à gauche, sections à droite
function legal($sommaire, $sections) {
    $h = '<div class="dato-legal"><aside><h3>Sommaire</h3><ul>';
    foreach ($sommaire as $s) { $h .= '<li>' . $s . '</li>'; }
    $h .= '</ul></aside><div class="dato-legal-body"><p><em>Dernière mise à jour : 1er janvier 2026</em></p>';
    foreach ($sections as $t => $p) { $h .= '<h3>' . $t . '</h3><p>' . $p . '</p>'; }
    return $h . '</div></div>';
}
$pages = [
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
foreach ($pages as $idn => $html) {
    $cm = $DB->get_record('course_modules', ['idnumber' => $idn, 'course' => 1]);
    $DB->update_record('page', (object) ['id' => $cm->instance, 'content' => $html, 'contentformat' => FORMAT_HTML, 'timemodified' => time()]);
    echo "$idn mis à jour\n";
}
purge_all_caches();
echo "ok\n";
