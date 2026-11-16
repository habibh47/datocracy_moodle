<?php
// Correctifs de la version anglaise : le bouton d'en-tête et les noms de catégories de cours ne passent pas par le filtre
// multilang (affichage des balises) ; on les remet en texte simple, la traduction est faite par le thème (datocracy.js).
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
global $DB;
set_config('header_btn_text', 'Inscription', 'theme_elan');
foreach ($DB->get_records('course_categories') as $cat) {
    if (preg_match('~<span lang="fr"[^>]*>(.*?)</span>~u', $cat->name, $m)) {
        $DB->update_record('course_categories', (object) ['id' => $cat->id, 'name' => $m[1]]);
        echo "catégorie {$cat->id} : {$m[1]}\n";
    }
}
purge_all_caches();