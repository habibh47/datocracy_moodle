<?php
// Catégorie « Cours en ligne » traduite (filtre multilang actif sur les titres).
define('CLI_SCRIPT', true);
require('/var/www/html/config.php');
global $DB;
$DB->set_field('course_categories', 'name', '<span lang="fr" class="multilang">Cours en ligne</span><span lang="en" class="multilang">Online course</span>', ['id' => 1]);
purge_all_caches();
echo "ok\n";