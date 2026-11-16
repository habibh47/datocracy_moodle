<?php
// Thème enfant : (1) logos et favicon sous le composant theme_datocracy (Moodle lit les fichiers du thème actif) ;
// (2) filtre « liens vers les noms d'activités » désactivé : il transformait le nom du cours, repris dans les attributs
// alt des images, en liens HTML cassés.
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
require_once($CFG->libdir . '/filterlib.php');
$fs = get_file_storage();
$ctx = context_system::instance();
foreach (['main_logo', 'mobile_logo', 'main_footer_logo', 'favicon'] as $area) {
    $setting = get_config('theme_elan', $area);
    $fs->delete_area_files($ctx->id, 'theme_datocracy', $area);
    foreach ($fs->get_area_files($ctx->id, 'theme_elan', $area, 0, 'id', false) as $f) {
        $fs->create_file_from_storedfile(['component' => 'theme_datocracy', 'contextid' => $ctx->id], $f);
        echo "$area : " . $f->get_filename() . "\n";
    }
    set_config($area, $setting, 'theme_datocracy');
}
filter_set_global_state('activitynames', TEXTFILTER_DISABLED);
purge_all_caches();
echo "ok\n";