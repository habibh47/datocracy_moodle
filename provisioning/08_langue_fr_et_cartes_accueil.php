<?php
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
require_once($CFG->libdir.'/adminlib.php');
global $DB;
$b = 'https://staging.datocracy.ai/theme/elan/pix/brand/';
// --- Accueil : section "Conçu pour vous" = 4 cartes icônes (Figma), image "Pourquoi" = mains empilées
$bi = $DB->get_record('block_instances', ['blockname'=>'elan_features_area', 'pagetypepattern'=>'site-index']);
if ($bi) {
  $cfg = new stdClass();
  $cfg->top_title='Conçu pour vous'; $cfg->title="Prenez place à la table de l'upskilling data";
  $cfg->content="Un apprentissage accessible et flexible, adapté à vos besoins : des cours fondamentaux et sectoriels en climat, santé et MPME.";
  $cfg->features_number=4;
  $items=[['Apprenez à votre façon','Des parcours flexibles, à votre rythme, sur tous vos appareils.','ri-time-line'],['Ancré dans la communauté','Apprenez avec des pairs et des praticiens engagés.','ri-group-line'],['Pensé pour la majorité mondiale','Des contenus accessibles et contextualisés.','ri-global-line'],["Acquérir des compétences data sous l'angle du développement mondial",'Des compétences data au service de l\'impact social.','ri-focus-3-line']];
  foreach ($items as $i=>$it){ $n=$i+1; $cfg->{"features_title$n"}=$it[0]; $cfg->{"features_content$n"}=$it[1]; $cfg->{"features_icon$n"}=$it[2]; }
  $DB->update_record('block_instances', (object)['id'=>$bi->id,'blockname'=>'elan_features_area_two','configdata'=>base64_encode(serialize($cfg))]);
  echo "features replaced\n";
}
foreach ($DB->get_records('block_instances', ['blockname'=>'elan_about_area']) as $r) {
  $c = unserialize(base64_decode($r->configdata)); $c->img = $b.'photo-meeting.png';
  $DB->set_field('block_instances','configdata',base64_encode(serialize($c)),['id'=>$r->id]);
}
// --- Pack de langue français
$ctrl = new \tool_langimport\controller();
$ok = $ctrl->install_languagepacks('fr');
echo "langpack fr: ".var_export($ok,true)."\n";
if (!empty($ctrl->info)) echo implode("\n",$ctrl->info)."\n";
if (!empty($ctrl->errors)) echo implode("\n",$ctrl->errors)."\n";
set_config('lang','fr');
set_config('langmenu', 1);
set_config('autolang', 0);
purge_all_caches();
echo "langs=".implode(',',array_keys(get_string_manager()->get_list_of_translations()))."\n";
